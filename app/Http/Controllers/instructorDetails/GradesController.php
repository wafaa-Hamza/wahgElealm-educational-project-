<?php

namespace App\Http\Controllers\instructorDetails;

use App\Http\Controllers\Controller;
use App\Models\Correct_answer;
use App\Models\Grade;
use App\Models\Question;
use App\Models\Student;
use App\Models\student_results;
use App\Models\Test;
use Illuminate\Http\Request;

class GradesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades = Grade::with('student', 'test')->get();
        return response()->json(['Grades'=>$grades]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'test_id' => 'required|exists:tests,id',
            'score' => 'required|integer',
            'title' => 'required|string',
        ]);

        $grade = Grade::create($validated);
        return response()->json([
            'message' => ' Grades Created Successfully',
            'data' => $grade,
        ], 200);
    }

public function submitAnswers(Request $request)

    {
        $studentId = $request->input('student_id');
        $answers = $request->input('answers');

        foreach ($answers as $questionId => $answer) {

            $question = Question::find($questionId);

         // return( $question);
            if ($question) {
                student_results::create([
                    'student_id' => $studentId,
                    'question_id' => $questionId,
                    'student_answer' => $answer,
                ]);
            } else {
                return response()->json(['error' => "Question ID $questionId does not exist"], 400);
            }
        }

        return response()->json(['message' => 'Answers submitted successfully']);
    }




    // public function calculateResults($studentId)
    // {
    //    // $studentId = $request->input('student_id');

    //     $studentAnswers = student_results::where('student_id', $studentId)->get();

    //     //return $studentId;
    //     $correctAnswersCount = 0;

    //     foreach ($studentAnswers as $studentAnswer) {
    //         $correctAnswer = Correct_answer::where('question_id', $studentAnswer->question_id)->first();
    //     // return  $correctAnswer;

    //         if ($correctAnswer && $correctAnswer->answer_text == $studentAnswer->student_answer	) {

    //             $correctAnswersCount++;
    //         }
    //     // return $studentAnswer->student_answer;
    //     }

    //     $totalQuestions = Question::count();
    //     $score = ($correctAnswersCount / $totalQuestions) * 100;


    //     return response()->json( [
    //         'studentId' => $studentId,
    //         'correctAnswersCount' => $correctAnswersCount,
    //         'totalQuestions' => $totalQuestions,
    //         'score' => $score
    //     ]);
    // }
                       ///////////// حساب درجات الطالب ف الاختبار ////////////////////////////////////
    public function calculateResults(Request $request)
    {
        $testId=$request->testId;
        $test = Test::with('questions')->findOrFail($testId);
       // return  $test;

        $students = Correct_answer::whereHas('question', function ($query) use ($testId) {
            $query->where('test_id', $testId);
        })->distinct()->pluck('student_id');
       // return $students;//10

        foreach ($students as $studentId) {
            $totalQuestions = $test->questions()->count(); //3
         //   return $totalQuestions;
            $correctAnswers = Correct_answer::whereHas('question', function ($query) use ($testId) {
                $query->where('test_id', $testId);

            })->where('student_id', $studentId)->where('is_correct', true)->count();
            $grade = ($correctAnswers / $totalQuestions) * 100;
            Grade::updateOrCreate(
                ['student_id' => $studentId, 'test_id' => $testId],
                ['grade' => $grade]
            );
        }

        return response()->json(['message' => 'Grades calculated successfully.']);
    }



   /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $grade = Grade::with('student', 'test')->findOrFail($id);
        return response()->json($grade);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'score' => 'integer',
        ]);

        $grade = Grade::findOrFail($id);
        $grade->update($validated);
        return response()->json([
            'message' => ' Grades Updated Successfully',
            'data' => $grade,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();
        return response()->json(null, 204);
    }
}
