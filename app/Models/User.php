<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Cart;
use App\Models\CartForStudent;
use App\Models\Course;
use App\Models\CoursesOfInstructor;
use App\Models\Rating;
use App\Models\SubscriptionInCourse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
  //  use SoftDeletes ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    //protected $table='course_user';

    public function ratings()
{
    return $this->hasMany(Rating::class);
}
    public function subscriptions()
{
    return $this->hasMany(SubscriptionInCourse::class);
}

        // public function courses()
        // {
        //     return $this->belongsToMany(CoursesOfInstructor::class, 'course_user', 'user_id', 'course_id');
        // }

        public function instructorCourses()
        {
            return $this->hasMany(CoursesOfInstructor::class, 'instructor_id');
        }

        public function favorites()
            {
                return $this->belongsToMany(Course::class, 'favorite_course', 'user_id', 'course_id');
            }

            public function carts1()
            {
                return $this->belongsToMany(CoursesOfInstructor::class, 'cart_for_students', 'user_id', 'courses_of_instructor_id');
            }

            public function carts2()
            {
                return $this->belongsToMany(Course::class, 'cart_for_users', 'user_id', 'course_id');
            }
            public function cart()
                {
                     return $this->hasOne(Cart::class);
                }
            public function cartStudent()
                {
                     return $this->hasOne(CartForStudent::class);
                }




    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
