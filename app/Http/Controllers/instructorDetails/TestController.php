<?php

namespace App\Http\Controllers\instructorDetails;

use App\Http\Controllers\Controller;
use App\Models\CoursesOfInstructor;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $test=Test::all();
       return $test;
    }
    public function closeTestSession(Request $request)     // انها
    {
        Auth::logout();

        return response()->json(['message' => 'Session closed successfully']);
    }
    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {

    //        $request->validate([

    //         'title' => 'required|string',
    //         'exam_time' => 'required|date_format:H:i',
    //         'exam_duration' => 'required|regex:/^\d{2}:\d{2}:\d{2}$/',
    //         'no_of_exams'=>'required|integer',
    //         'is_completed'=>'required|boolean',
    //         'courses_of_instructor_id'=>'required|integer',
    //         'no_of_questions' => 'required|string',



    //     ]);
    // //     if ($request->hasFile('image')) {

    // //         $image = $request->file('image');
    // //         $filename = 'image'.time() . '.' . $image->getClientOriginalExtension();
    // //         $image->storeAs('public/images/', $filename);
    // //     }

    // //   $Testtore=  test::create($request->all());
    // //   if (isset($filename)) {
    // //     $Testtore->image = $filename;
    // // }
    // // $Testtore->save();
    // $Testtore=  test::create($request->all());
    //     return response()->json([
    //         'message' => 'Test Added Successfully',
    //         'data'    =>  $Testtore ,

    //     ], 201);
    // }



    public function CalcReminingPercentage($id)
    {
        $test=Test::find($id);

        $course = CoursesOfInstructor::find($id);
        $testCount = $course->tests()->count();
        $CompletedTests = $course->tests()->where('tests.is_completed',1)->count();


        $reminingPercentage=($testCount -$CompletedTests) / $testCount *100;    //الجزء المتبقي

        $test->reminigation = $reminingPercentage;
        $test->save();
        return response()->json(['message' => 'All remingations updated successfully.']);

    }



    /**
     * Display the specified resource.
     */
    public function showQuestions(string $id)
    {
         $TestShow=Test::findOrFail($id);

         $questions = $TestShow->questions;
         foreach ($questions as $question) {
            return $question->question_text;
            return $question->question_text;
        }



    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'title' => 'string',
            'duration' => 'date',
            'exam_time' => 'date_format:H:i',
            'exam_duration' => 'regex:/^\d{2}:\d{2}:\d{2}$/',
            'no_of_questions' => 'string',
            'is_completed' => 'boolean',
            'question_id'=>'integer',
            'grade_id'=>'integer',
            'correct_answer' => 'string',
            'no_of_exams'=>'integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'

        ]);
        $test=Test::findOrFail($id);
        $TestsData = $request->except(['image']);;

        if ($request->hasFile('image')) {

            Storage::delete('public/images/'.$test->image);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'image'.time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images/', $filename);
            $TestsData['image'] = $filename;
        }
        $test->update($TestsData);
        return response()->json([
            'message' => 'Student Details Updated Successfully',
            'data' => $test,
        ], 200);
    }


    public function next( Request $request)
    {
        $nextButton=DB::table('tests')->insert([
                'no_of_exams'=>$request->no_of_exams,
                'title'=>$request->title,
                'duration'=>$request->duration,
                'exam_duration'=>$request->exam_duration,
                'exam_time'=>$request->exam_time,
                'question'=>$request->question,
                'no_of_questions'=>$request->no_of_questions,
                'image'=>$request->image

        ]);
        return $nextButton;
        return back();
    }

    public function completed_exam()
    {
   return redirect()->route('test');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $InsDataDestroy=Test::where('id',$id)->delete();
        return response()->json([
      'status'     => true,
      'message'    => 'Test Deleted Successfully',
  ],201);
}
    }

