<?php

namespace App\Models;

use App\Models\User;
use App\Models\instructor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $guarded=['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Instructor()
    {
        return $this->belongsTo(instructor::class);
    }
}
