<?php

namespace App\Models;

use App\Models\CoursesOfInstructor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDay extends Model
{
    use HasFactory;
    protected $guardr=['id'];

    public function course()
    {
        return $this->belongsTo(CoursesOfInstructor::class, 'courses_of_instructor_id');
    }
}
