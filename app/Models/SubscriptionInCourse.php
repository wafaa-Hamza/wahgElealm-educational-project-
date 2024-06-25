<?php

namespace App\Models;

use App\Models\CoursesOfInstructor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionInCourse extends Model
{
    use HasFactory;

    protected $guarded=['id'];

    public function coursesofinstrts()
    {
        return $this->hasMany(CoursesOfInstructor::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }

}
