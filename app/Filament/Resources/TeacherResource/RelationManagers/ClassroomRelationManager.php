<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Periode;
use Filament\Forms\Form;
use App\Models\Classroom;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ClassroomRelationManager extends RelationManager
{
    protected static string $relationship = 'classroom';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('classrooms_id')
                ->label('Select Class')
                ->options(Classroom::all()->pluck('name', 'id'))
                ->searchable()
                ->relationship('classroom', 'name')
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->required()                                                 
                ])
                ->createOptionAction(function (Forms\Components\Actions\Action $action){ // Fixed typo in namespace
                    return $action 
                        ->modalHeading('Add Classroom')
                        ->modalButton('Add Classroom')
                        ->modalWidth('2xl');
                }),
            Select::make('periode_id')
                ->label('Select Periode')
                ->options(Periode::all()->pluck('name', 'id'))
                ->searchable()
                ->relationship('periode', 'name')
                ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->required()
                                                
                ])
                ->createOptionAction(function (Forms\Components\Actions\Action $action){ // Fixed typo in namespace
                    return $action 
                        ->modalHeading('Add Periode')
                        ->modalButton('Add Periode')
                        ->modalWidth('2xl');
                }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('classroom.name')->label('Ruang Kelas'),
                TextColumn::make('periode.name')->label('Periode'),
                ToggleColumn::make('is_open')->label('Status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


}

