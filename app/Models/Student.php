<?php

namespace App\Models;

use App\Models\CoursesOfInstructor;
use App\Models\student_results;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function courses()
    {
        return $this->belongsToMany(CoursesOfInstructor::class,'courses_of_instructor_student','student_id','courses_of_instructor_id');
    }
    public function answers()
    {
        return $this->belongsToMany(student_results::class);

   }


}
