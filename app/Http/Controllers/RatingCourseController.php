<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RatingCourse;
use App\Models\Teacher;
use Illuminate\Http\Request;

class RatingCourseController extends Controller
{
    public function storeCourse(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'rating' => 'required|double|between:1,5',
            'comment' => 'nullable|string',
        ]);

        RatingCourse::create([
            'user_id' =>$request->user_id,
            'teacher_id' => $request->teacher_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
}

public function showRateOfCourse(Request $request)
{
    $ratings = Teacher::with('RatingCourse','RatingCourse.user')->get();

    $totalRatings = $ratings->count();

    $sumRatings = RatingCourse::where('teacher_id',$request->teacher_id)->sum('rating');

    $averageRating = $totalRatings > 0 ? $sumRatings / $totalRatings : 0;


    return response()->json([
        'averageRating' => $averageRating,
//'ratings' => $ratings,
    ]);
}

}
