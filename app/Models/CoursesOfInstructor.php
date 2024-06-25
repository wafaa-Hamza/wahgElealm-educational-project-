<?php

namespace App\Models;
use App\Models\CartForStudent;

use App\Models\CourseDay;
use App\Models\Lecture;
use App\Models\Order;
use App\Models\Student;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class CoursesOfInstructor extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function instructor()
    {
        return $this->belongsTo(instructor::class);
    }


    public function classes()
        {
            return $this->hasMany(classModel::class);
        }
    public function lectures()

    { return $this->belongsToMany(Lecture::class, 'courses_of_instructor_lecture', 'courses_of_instructor_id', 'lecture_id');
    }
    public function tests()
    {
        return $this->belongsToMany(Test::class,'courses_of_instructor_test','courses_of_instructor_id','test_id');
    }
    public function students()
    {
        return $this->belongsToMany(Student::class,'courses_of_instructor_student','courses_of_instructor_id','student_id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function cartForStudents()
    {
        return $this->belongsToMany(CartForStudent::class, 'cart_for_student_course', 'courses_of_instructor_id', 'cart_for_student_id');
    }

      public function orders()
    {
        return $this->hasMany(Order::class);
    }


    public function days()
    {
        return $this->hasMany(CourseDay::class, 'courses_of_instructor_id');
    }
}
