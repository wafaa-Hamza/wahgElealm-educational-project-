<?php

namespace App\Models;

use App\Models\CoursesOfInstructor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Relationship with Course
    public function courseofinstr()
    {
        return $this->belongsTo(CoursesOfInstructor::class);
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
