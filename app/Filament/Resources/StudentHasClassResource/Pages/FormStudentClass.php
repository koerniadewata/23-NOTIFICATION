<?php

namespace App\Filament\Resources\StudentHasClassResource\Pages;


use App\Models\Periode;
use App\Models\Student;
use App\Models\HomeRoom;
use App\Models\StudentHasClass;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tab; // tambahkan ini
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Filament\Resources\StudentHasClassResource;


class FormStudentClass extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = StudentHasClassResource::class;

    protected static string $view = 'filament.resources.student-has-class-resource.pages.forms-student-class';


    public ?array $students = [];
    public $homerooms = '';
    public $periode = '';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function getFormSchema(): array
    {
        return [
            Tabs::make('Tabs')
            ->tabs([
                [
                    'label' => 'Tab 1',
                    'fields' => [
                        Select::make('students_id')
                            ->searchable()
                            ->multiple()
                            ->options(Student::all()->pluck('name', 'id'))                        
                            ->columnSpan(3),
                        Select::make('homerooms_id')
                            ->searchable()
                            ->options(HomeRoom::all()->pluck('classroom.name', 'id'))
                            ->label('Class'),
                        Select::make('periode_id')
                            ->searchable()
                            ->options(Periode::all()->pluck('name', 'id'))
                            ->label('Periode')
                    ],
                    'columns' => 3
                ]
            ])               
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