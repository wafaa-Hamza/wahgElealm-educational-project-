<?php

namespace App\Models;

use App\Models\CoursesOfInstructor;
use App\Models\Question;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class student_results extends Model
{
    use HasFactory;

    protected $guarded=['id'];


    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
