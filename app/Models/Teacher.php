<?php

namespace App\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function RatingCourse()
    {
        return $this->hasMany(RatingCourse::class);
    }
    public function Courses()
    {
        return $this->hasMany(Course::class);
    }
}
