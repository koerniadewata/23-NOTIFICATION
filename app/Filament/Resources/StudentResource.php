<?php

namespace App\Filament\Resources;

use stdClass;
use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Table\Action\CreateAction;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\StudentResource\Pages;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nis')
                                    ->label('NIS'),
                                TextInput::make('name')
                                    ->label('Student Name')
                                    ->required(),
                                Select::make('gender')
                                    ->options([
                                        "Male" => "Male",
                                        "Female" => "Female"
                                    ]),
                                DatePicker::make('birthday')
                                    ->label('Birthday'),
                                    Select::make('religion')
                                        ->options([
                                        "Islam" => "Islam",
                                        "Katholik" => "Katholik",
                                        "Protestan" => "Protestan",
                                        "Hindu" => "Hindu",
                                        "Budha" => "Budha",
                                        "Konghuchu" => "Konghuchu"
                                        ]),
                                TextInput::make('contact'),
                                FileUpload::make('profile')
                                    ->directory('students')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) (
                            (int)$rowLoop->iteration +
                            ((int)$livewire->getTableRecordsPerPage() * (
                                (int)$livewire->getTablePage() - 1
                            ))
                        );
                    }
                ),
                TextColumn::make('nis')
                    ->label('NIS'),
                TextColumn::make('name')
                    ->label('Student Name'),
                TextColumn::make('gender'),
                TextColumn::make('birthday')
                    ->label('Birthday'),
                TextColumn::make('religion'),
                TextColumn::make('contact'),
                ImageColumn::make('profile'),
                TextColumn::make('status')
                    ->formatStateUsing(fn (string $state): string => ucwords("{$state}"))
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('Accept')
                        ->icon('heroicon-m-check')
                        ->requiresConfirmation()
                        ->action(function (Collection $records)
                        {
                            return $records->each->update(['status'=>'accept']);
                        }),
                    BulkAction::make('Off')
                        ->icon('heroicon-m-x-circle')
                        ->requiresConfirmation()
                        ->action(function (Collection $records)
                        {
                            return $records->each(function ($record)
                            {
                                $id = $record->id;
                                Student::where('id', $ids)->update(['status'=>'off']);
                            });
                        }),
                        
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),
            // ])

            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'view' => Pages\ViewStudent::route('/{record}'),
        ];
    }

    public static function getLabel(): ? string
    {
        $locale = app()->getLocale();
        if($locale == 'id')
        {
            return "Murid";
        }else
        {
            return "Students";
        }
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('nis'),
                TextEntry::make('name')
            ]);
    }
}
