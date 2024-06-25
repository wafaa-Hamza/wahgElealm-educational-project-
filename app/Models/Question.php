<?php

namespace App\Models;

use App\Models\Correct_answer;
use App\Models\Student;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function exam()
    {
        return $this->belongsTo(Test::class, 'exam_id');
    }
    public function correctAnswers()
    {
        return $this->hasMany(Correct_answer::class);
    }


}
