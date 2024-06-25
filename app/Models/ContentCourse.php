<?php

namespace App\Models;

use App\Models\CoursesOfInstructor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentCourse extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function CoursesOfInstructor()
    {
        return $this->belongsTo(CoursesOfInstructor::class);
    }
}
