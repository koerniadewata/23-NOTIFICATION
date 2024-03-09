<?php

namespace App\Filament\Resources\StudentClassResource\Pages;

use App\Models\Periode;
use App\Models\Student;
use App\Models\HomeRoom;
use App\Models\StudentHasClass;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Filament\Resources\StudentClassResource;

class FormStudentClass extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = StudentClassResource::class;

    protected static string $view = 'filament.resources.student-class-resource.pages.form-student-class';
    public $students = [];
    public $homeroom = '';
    public $periode = '';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function getFormSchema(): array
    {
        return [

            Section::make('Rate limiting')
                ->description('Prevent abuse by limiting the number of requests per period')
                ->schema([
                    Select::make('students')
                        ->multiple()
                        ->label('Name Student')
                        ->options(Student::all()->pluck('name', 'id'))                        
                        ->columnSpan(3),
                    Select::make('homerooms')
                        ->searchable()
                        ->options(HomeRoom::all()->pluck('classroom.name', 'id'))
                        ->label('Class'),
                    Select::make('periode')
                        ->label('Periode')
                        ->options(Periode::all()->pluck('name', 'id')),
                ])
                ->columns(3)                    
        ];
    }

    public function save()
    {
        $students = $this->students;
        $insert = [];
        foreach($students as $row)
        {
            array_push($insert, [
                'students_id' => $row,
                'homerooms_id' => $this->homerooms,
                'periode_id' => $this->periode,
                'is_open' => 1
            ]);
        }
        StudentHasClass::insert($insert);

        return redirect()->to('admin/student-has-classes');
    }

}