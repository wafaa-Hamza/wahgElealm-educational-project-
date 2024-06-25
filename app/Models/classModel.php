<?php

namespace App\Models;

use App\Models\Course;
use App\Models\CoursesOfInstructor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class classModel extends Model
{
    use HasFactory;

    protected $table = 'class_models';
    protected $fillable = ['title', 'price', 'courses_of_instructor_id', 'instructor_id', 'created_at'];

    public function course()
    {
        return $this->belongsTo(CoursesOfInstructor::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
}
