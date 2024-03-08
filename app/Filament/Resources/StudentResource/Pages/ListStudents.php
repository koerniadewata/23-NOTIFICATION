<?php

namespace App\Filament\Resources\StudentResource\Pages;

use Filament\Actions;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\StudentResource;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getHeader(): ?View
    {
        $data = Actions\CreateAction::make();
        return view('filament.custom.upload-file', compact('data'));
    }

    public $file = '';

    public function save()
    {
        Student::create([
            'nis' => '123',
            'name' => 'Try First',
            'gender' => 'Male',
        ]);
    }
}
