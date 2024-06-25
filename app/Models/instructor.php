<?php

namespace App\Models;

use App\Models\CoursesOfInstructor;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class instructor extends Model
{
    use HasFactory;
    protected $guarded =['id'];

    public function coursesofinstrts()
    {
        return $this->hasMany(CoursesOfInstructor::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    // public function averageRating()
    // {
    //     return $this->ratings()->avg('rating');
    // }
    public function classes()
    {
        return $this->hasMany(classModel::class);
    }
}

