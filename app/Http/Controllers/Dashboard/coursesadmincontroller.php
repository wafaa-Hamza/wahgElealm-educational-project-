<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class coursesadmincontroller extends Controller
{
    public function index(Request $request)
            {
            $course_name = $request->course_name;

            if ($course_name) {
                $courses = Course::where("course_name", "like", "%" . $course_name . "%")->get();
            } else {
                $courses = Course::all();
            }
            $coursesData = $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'course_name' => $course->course_name,
                    'level' => $course->level,
                    'created_at' => $course->created_at,
                ];
            });

            return response()->json(['coursesData' => $coursesData]);
        }


        public function indexData()
        {
            $recentCoursesCount = Course::where('created_at', '>=', now()->subDays(30))->count();
            $totalCoursesCount = Course::count();
            $activeTeachersCount = Teacher::has('courses')->count();
            $totalTeachersCount = Teacher::count();


            return response()->json([
                'recentCoursesCount' => $recentCoursesCount,
                'totalCoursesCount' => $totalCoursesCount,
                'activeTeachersCount' => $activeTeachersCount,
                'totalTeachersCount' => $totalTeachersCount,
            ]);
        }




    public function store(Request $request)
    {
        $new_course = new Course();
        $new_course->course_name = $request->input('course_name');
        $new_course->level = $request->input('level');
        $new_course->teacher_id = $request->input('teacher_id');
        $new_course->description = $request->input('description');
        $new_course->price = $request->input('price');
        $new_course->save();

        return response()->json(['message' => 'course added succesfully  ']);
    }

    public function destroy($id)
    {
$courseDestroy=Course::where('id',$id)->delete();
return response()->json([
    'status'     => true,
    'message'    => 'Course Deleted Successfully',
],201);
}
}
