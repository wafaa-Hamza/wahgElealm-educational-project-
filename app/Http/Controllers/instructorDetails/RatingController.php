<?php

namespace App\Http\Controllers\instructorDetails;

use App\Http\Controllers\Controller;
use App\Models\instructor;
use App\Models\Rating;
use App\Models\Teacher;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'instructor_id' => 'required|exists:instructors,id',
            'rating' => 'required|double|between:1,5',
            'comment' => 'nullable|string',
        ]);

        Rating::create([
            'user_id' =>$request->user_id,
            'instructor_id' => $request->instructor_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json ('success', 'Rating submitted successfully.');
    }

    public function show(Request $request)
    {
        $ratings = instructor::with('Ratings','Ratings.user')->get();

        $totalRatings = $ratings->count();

        $sumRatings = Rating::where('instructor_id',$request->instructor_id)->sum('rating');

        $averageRating = $totalRatings > 0 ? $sumRatings / $totalRatings : 0;


        return response()->json([
            'averageRating' => $averageRating,

        ]);
}

}
