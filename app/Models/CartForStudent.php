<?php

namespace App\Models;

use App\Models\CoursesOfInstructor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class CartForStudent extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function courses()
    {
        return $this->belongsToMany(CoursesOfInstructor::class, 'cart_for_student_course', 'cart_for_student_id', 'courses_of_instructor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
