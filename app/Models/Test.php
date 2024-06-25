<?php

namespace App\Models;

use App\Models\CoursesOfInstructor;
use App\Models\Grade;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    // public function grades()
    // {
    //     return $this->hasMany(Grade::class);
    // }

    public function courses()
    {
        return $this->belongsToMany(CoursesOfInstructor::class,'courses_of_instructor_test');
    }
}
