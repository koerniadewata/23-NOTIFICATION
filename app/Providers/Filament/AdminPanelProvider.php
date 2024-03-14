<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Navigation\UserMenuItem;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\PageResource;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\PeriodeResource;
use App\Filament\Resources\StudentResource;
use App\Filament\Resources\SubjectResource;
use App\Filament\Resources\TeacherResource;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\ClassroomResource;
use App\Filament\Resources\DepartmentResource;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Resources\CategoryNilaiResource;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->sidebarCollapsibleOnDesktop(true)
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])

            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make()
                    ->items([
                        NavigationItem::make('Dashboard')
                        ->icon('heroicon-o-home')
                        ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                        ->url(fn (): string => Dashboard::getUrl()),
                    ]),
                    NavigationGroup::make('Academic')
                    ->items([
                        ...TeacherResource::getNavigationItems(),
                        ...StudentResource::getNavigationItems(),
                        ...SubjectResource::getNavigationItems(),
                    ]),
                    NavigationGroup::make('Source')
                        ->items([
                            ...CategoryNilaiResource::getNavigationItems(),
                            ...ClassroomResource::getNavigationItems(),
                            ...DepartmentResource::getNavigationItems(),
                        ]),
                        NavigationGroup::make('Setting')
                        ->items([
                            ...PeriodeResource::getNavigationItems(),
                            NavigationItem::make('Roles')
                                ->icon('heroicon-o-user-group')
                                ->isActiveWhen(fn (): bool => request()->routeIs([
                                    'filament.admin.resources.roles.index',
                                    'filament.admin.resources.roles.create',
                                    'filament.admin.resources.roles.view',
                                    'filament.admin.resources.roles.edit'
                                ]))
                                ->url(fn (): string => '/admin/roles'),
                                NavigationItem::make('Permissions')
                                ->icon('heroicon-o-lock-closed')
                                ->isActiveWhen(fn (): bool => request()->routeIs([
                                    'filament.admin.resources.permissions.index',
                                    'filament.admin.resources.permissions.create',
                                    'filament.admin.resources.permissions.view',
                                    'filament.admin.resources.permissions.edit'
                                ]))
                                ->url(fn (): string => '/admin/permissions'),
                        ]),
                ]);
            });
    }

    public function boot():void
    {
        Filament::serving(function ()
        {
            Filament::registerUserMenuItems([
            UserMenuItem::make()
            ->label('Setting')
            ->url(PeriodeResource::getUrl())
            ->icon('heroicon-s-cog'),
        ]);

        });
    }
}


