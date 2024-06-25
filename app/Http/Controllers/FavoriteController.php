<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function addToFavorites(Request $request)
    {
        $courseId = $request->input('course_id');

       // return $courseId;
        $user = Auth::user();

        $user->favorites()->attach($courseId);
        $favorites = $user->favorites()->get();


        return response()->json([
            'status' => true,
            'message' => 'Course added to favorites successfully',
            'favorites' => $favorites,
        ], 201);

    }
    public function removedFromaFavorites(Request $request)
        {
            $courseId = $request->input('course_id');


            $courseId = $request->input('course_id');
            $user = Auth::user();

            $user->favorites()->detach($courseId);
            $favorites = $user->favorites()->get();

            return response()->json([
                'status' => true,
                'message' => 'Course removed from favorites successfully',
                'favorites' => $favorites,

            ], 200);
        }




    public function get_Search_Favorites(Request $request)
    {
        $user = Auth::user();

        $favorites = $user->favorites()->with('teacher');


        if ($request->has('course_name') && !empty($request->input('course_name'))) {
            $favorites->where('course_name', 'like', '%'  . $request->input('course_name') .'%');
        }

        if ($request->has('teacher_name') && !empty($request->input('teacher_name'))) {
            $teacher_name = $request->input('teacher_name');
            $favorites->whereHas('teacher', function ($q) use ($teacher_name) {
                $q->where('name', 'like', "%$teacher_name%");
            });
        }

        $favorites = $favorites->get();

        return response()->json([
            'message' => 'Success',
            'favorites' => $favorites,
        ], 200);
}
}
