<?php

namespace App\Models;

use App\Models\CoursesOfInstructor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function courses_lectures()
    {
        return $this->belongsToMany(CoursesOfInstructor::class, 'courses_of_instructor_lecture', 'lecture_id', 'courses_of_instructor_id');
    }

}
