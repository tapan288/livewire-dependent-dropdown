<?php

namespace App\Http\Livewire\Student;

use App\Models\Student;
use Livewire\Component;

class Edit extends Component
{
    public Student $student;
    public $sections = [];
    public $selectedClass = null, $selectedSection = null;

    public function mount()
    {
        $this->selectedClass = $this->student->class_id;
        $this->selectedSection = $this->student->section_id;
        $this->sections = \App\Models\Section::where("class_id", $this->selectedClass)->get();
    }

    protected $rules = [
        'student.name' => 'required|string|min:6',
        'student.email' => 'required|email',
        'student.phone_number' => 'required|string',
    ];

    public function render()
    {
        return view('livewire.student.edit', [
            "classes" => \App\Models\Classes::all(),
        ]);
    }

    public function update()
    {
        $this->validate($this->rules + [
            "selectedClass" => "required",
            "selectedSection" => "required",
        ]);

        $this->student->class_id = $this->selectedClass;
        $this->student->section_id = $this->selectedSection;

        $this->student->save();

        session()->flash("success", "Student created successfully");

        return redirect()->route("students.index");
    }

    public function updatedSelectedClass($value)
    {
        $this->sections = \App\Models\Section::where("class_id", $value)->get();
    }
}
