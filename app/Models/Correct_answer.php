<?php

namespace App\Models;

use App\Models\Question;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correct_answer extends Model
{
    use HasFactory;

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
