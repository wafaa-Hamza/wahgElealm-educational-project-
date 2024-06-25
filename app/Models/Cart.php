<?php

namespace App\Models;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;



    protected $guarded = ['id'];


    public function courses()
    {
        return $this->belongsToMany(Course::class, 'cart_course');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
