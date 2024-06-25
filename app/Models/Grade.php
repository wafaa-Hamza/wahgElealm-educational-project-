<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
