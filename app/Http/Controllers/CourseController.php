<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;


class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();
           return $courses;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'course_name'     => 'required|max:254',
                'teacher_id'     => 'required||integer',
                'description' => 'required|max:254',
                'price' => ['required', 'regex:/^\d{1,6}\.\d{2}$/'],
                'level' => 'string',
            ]);


            $courses = Course::create([
                'course_name'=>$request->course_name,
                'teacher_id'=>$request->teacher_id,
                'description'=>$request->description,
                'price'=>$request->price,
                'level'=>$request->level,

                //'image'=>$request->image
            ]);
            // if (isset($filename)) {
            //     $courses->image = $filename;
            // }
            // $courses->save();


            return response()->json([
                'message' => 'Course Created Successfully',
                'data'    =>  $courses ,
            ], 201);

        }catch (ValidationException $e) {
            return response()->json([
                'message'   => 'Validation Error',
                'errors'    => $e->errors(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return response()->json($course->id::all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
            $request->validate([
                'course_name'     => 'max:254',
                'teacher_id'     => 'integer',
                'description' => 'max:254',
                'price' => ['regex:/^\d{1,6}\.\d{2}$/'],
                'level' => 'string',
              ///  'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);



             $coursesData = Course::where('id',$id)->first();
            // if ($request->hasFile('image')) {

            //     Storage::delete('public/images/'.$coursesData->image);
            // }

            // if ($request->hasFile('image')) {
            //     $image = $request->file('image');
            //     $filename = 'image'.time() . '.' . $image->getClientOriginalExtension();
            //     $image->storeAs('public/images/', $filename);
            // }
            $course = Course::where('id',$id)->update([
                'course_name'     => (!empty($request->name))?$request->name:$coursesData->course_name,
                'teacher_id'     => (!empty($request->teacher_id))?$request->teacher_id:$coursesData->teacher_id,
                'description' => (!empty($request->description))?$request->description:$coursesData->description,
                'price' => (!empty($request->price))?$request->price:$coursesData->price,
                'level' => (!empty($request->level))?$request->level:$coursesData->level,
            ]);
            $course  = Course::where('id', $id)->get();

            return response(['data' =>  ' Course Updated successfully' ], 200);
    }

    public function Search_In_Courses(Request $request)
    {
        $request->validate([
            'course_name' => 'nullable|string',
            'teacher_name' => 'nullable|string',
            'level' => 'nullable|string',
        ]);

        $SearchCourses = Course::with('teacher');

        if ($request->has('course_name') && !empty($request->input('course_name'))) {
            $course_name = $request->input('course_name');
            $SearchCourses->where('course_name', 'like', "%$course_name%");
        }

        if ($request->has('level') && !empty($request->input('level'))) {
            $level = $request->input('level');
            $SearchCourses->where('level', 'like', "%$level%");
        }

        if ($request->has('teacher_name') && !empty($request->input('teacher_name'))) {
            $teacher_name = $request->input('teacher_name');
            $SearchCourses->whereHas('teacher', function ($q) use ($teacher_name) {
                $q->where('name', 'like', "%$teacher_name%");
            });
        }

        $SearchCourses = $SearchCourses->get();

        return response()->json([
            'message' => 'Success',
            'data' => $SearchCourses,
        ], 200);
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
            {
        $courseDestroy=Course::where('id',$id)->delete();
        return response()->json([
            'status'     => true,
            'message'    => 'Course Deleted Successfully',
        ],201);
        }
    }

