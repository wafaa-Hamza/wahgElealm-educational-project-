<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Teacher;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Course extends Model
 {
            use HasFactory;
        protected $guarded=[];

    //     public function carts()
    // {
    //     return $this->belongsToMany(Cart::class, 'cart_course', 'course_id', 'cart_id')
    //                 ->withPivot('quantity', 'total_Price', 'discounted_price');
    // }
        // public function users()
        // {
        //     return $this->belongsToMany(User::class, 'favorite_course')->withTimestamps();
        // }
        public function teacher()
        {
            return $this->belongsTo(Teacher::class);
        }
        public function favoredBy()
        {
            return $this->belongsToMany(User::class, 'favorite_course', 'course_id', 'user_id');
        }

        public function classes()
        {
            return $this->hasMany(classModel::class);
        }


        public function cartUsers()
        {
            return $this->belongsToMany(User::class, 'cart_for_users', 'course_id', 'user_id');
        }



}
