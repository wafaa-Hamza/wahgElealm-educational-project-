<?php

namespace App\Http\Controllers\instructorDetails;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CoursesOfInstructor;
use App\Models\Lecture;
use App\Models\Question;
use App\Models\Student;
use App\Models\Test;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CoursesOfInstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
//     public function index()
//     {
// $GetCourses= CoursesOfInstructor::with('instructor')->get();
// return $GetCourses;
//     }


public function getCourses()
{
        $GetCourses= CoursesOfInstructor::with('instructor','instructor.ratings')->get();
        return response()->json(['getCoursesses'=>$GetCourses]);
}

public function getCompletedCourses()
{
        $getCompletedCourses= CoursesOfInstructor::with('instructor','instructor.ratings')->where('is_completed',1)->get();
        return response()->json(['Completed Courses'=> $getCompletedCourses]);
}




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'course_name' => 'required|max:100',
            'educational_level' => 'required|string',
            'deviced_used' => 'required|in:mobile,computer',
            'instructor_id' => 'required|integer',
             'subscribed_by' => 'required|integer',
            // 'test_id' => 'required|integer',
            'duration' => 'required|string',
            'price' =>  ['required', 'regex:/^\d{1,6}\.\d{2}$/'],
            'quantity' =>  'required|integer',
            'bought' => 'required|boolean',
            'is_subscriped' => 'required|boolean',
            'is_completed' => 'required|boolean',
            // 'student_id' => 'required|integer',
            'no_of_days' => 'required|integer',
            'video' => 'sometimes|file|mimes:mp4,mov,avi,flv|max:20480',
            'day' => 'required|string',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'no_of_lectures' => 'required|integer',
        ]);
        $filename = null;
    if ($request->hasFile('video')) {
        $file = $request->file('video');
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = storage_path('app/private/videos/' . $filename);

        // تشفير الفيديو قبل تخزينه
        $videoContent = file_get_contents($file->getRealPath());
        $encryptedContent = openssl_encrypt($videoContent, 'AES-256-CBC', env('ENCRYPTION_KEY'), 0, '1234567890123456');

        file_put_contents($path, $encryptedContent);
    }

    $data = $request->all();
    $data['video'] = $filename;

      $CoursesOfInstructorStore=  CoursesOfInstructor::create($data);

     $CoursesOfInstructorStore->save();

        return response()->json([
            'message' => 'Course Created Successfully',
            'data'    =>  $CoursesOfInstructorStore ,

        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)      //عرض الكورس بتفاصيله
    {
        $CoursesOfInstructorShow=CoursesOfInstructor::findOrFail($id);

        $CoursesOfInstructor = CoursesOfInstructor::with(['instructor', 'lectures', 'tests'])->get();
        return response()->json([
            'courses' => $CoursesOfInstructor->map(function ($course) {
                return [
                    'course_name' => $course->course_name,
                    'duration' => $course->duration,
                    'no_of_lectures' => $course->no_of_lectures,
                    'deviced_used' => $course->deviced_used,
                    'video' => $course->video,
                   // 'reminigation' => $course->reminigation,


                    'instructor' => $course->instructor ? [
                        'name' => $course->instructor->name,
                        'description' => $course->instructor->description,
                        'image' => $course->instructor->image,
                    ] : 'N/A',

                    'lectures' => $course->lectures->map(function ($lecture) {
                        return [
                            'title' => $lecture->title,
                            'duration' => $lecture->duration,
                            'is_completed' => $lecture->is_completed,
                        ];
                    }),

                    'tests' => $course->tests->map(function ($test) {
                        return [
                            'title' => $test->title,
                            'reminigation' => $test->reminigation,
                        ];
                    }),
                    'days' => $course->days->pluck('day'),
                ];

            }),
        ], 200);


    }


        public function showVideo($id)
        {
            $course = CoursesOfInstructor::findOrFail($id);

            if (!$course->video) {
                abort(404, 'Video not found');
            }

            $path = storage_path('app/private/videos/' . $course->video);

            if (!file_exists($path)) {
                return response()->json(['message' => 'Video file not found'], 404);
            }

            $encryptedContent = file_get_contents($path);
            $videoContent = openssl_decrypt($encryptedContent, 'AES-256-CBC', env('ENCRYPTION_KEY'), 0, '1234567890123456');

            return response($videoContent)->header('Content-Type', 'video/mp4');

        }


/**
     * showww the specified resource in storage.
     */



    public function getCourseDetails(Request $request)             //  تفاصيل الكورس وحساب النسب والمكتمل والمتبقي وعدد مستخدمين الجهاز
    {
        $id=$request->id;

        $course = CoursesOfInstructor::with('students','orders','instructor','tests')->find($id);
        $testCount = $course->tests()->count();
        $CompletedTests = $course->tests()->where('tests.is_completed',1)->count();
        $totalLectures = $course->no_of_lectures;
        $completedLectures = $course->lectures()->where('is_completed', 1)->count();

    // return   $completedLectures;

        if ($course) {
            $totalSales = $course->orders->sum('amount');
            $studentCount = $course->students->count();
            $remainingLectures = $course->no_of_lectures -$completedLectures;
            $remainingTests = $course->tests()->count() -$CompletedTests;



            return response()->json([
                'course_name' => $course->course_name,
                'total_sales' => $totalSales,
                'student_count' => $studentCount,
                'remainingLectures' => $remainingLectures,
                'remainingTests' => $remainingTests,
                'course_progress' => [
                    'completedLectures' => ( $completedLectures / $course->no_of_lectures) * 100,
                    'remainingPercentageLecture' => ($remainingLectures / $course->no_of_lectures) * 100,
                    'completedTests' => ( $CompletedTests / $course->tests()->count()) * 100,
                    'remainingPercentageTests' => ($remainingTests /$course->tests()->count()) * 100,
                ],
                'device_usage' => [
                    'computer_users' => $course->students->where('devices_used', 'computer')->count(),
                    'mobile_users' => $course->students->where('devices_used', 'mobile')->count(),
                ],
                'course_duration' => $course->duration,
                'start_date' => $course->from_date,
                'end_date' => $course->to_date,
                'days' => $course->days->pluck('day'),
            ]);
    }

    }





    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
     $data=$request->validate([
            'course_name' => 'max:100',
            'educational_level' => 'string',
            'deviced_used' => 'in:mobile,computer',
            'instructor_id' => 'integer',
             'subscribed_by' => 'integer',
            // 'test_id' => 'required|integer',
            'duration' => 'string',
            'price' =>  ['regex:/^\d{1,6}\.\d{2}$/'],
            'quantity' =>  'integer',
            'bought' => 'boolean',
            'is_subscriped' => 'boolean',
            'is_completed' => 'boolean',
            // 'student_id' => 'required|integer',
            'no_of_days' => '|integer',
            'video' => 'sometimes|file|mimes:mp4,mov,avi,flv|max:20480',
            'day' => 'string',
            'from_date' => 'date',
            'to_date' => 'date',
            'no_of_lectures' => 'integer',
        ]);
     //  dd($request->is_subscribed);


      $CoursesOfInstructorUpdate=  CoursesOfInstructor::findOrFail($id);

      if ($CoursesOfInstructorUpdate->video) {
        $oldVideoPath = storage_path('app/private/videos/' . $CoursesOfInstructorUpdate->video);
        if (file_exists($oldVideoPath)) {
            unlink($oldVideoPath);
        }
    }
    if ($request->hasFile('video')) {
        $file = $request->file('video');
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = storage_path('app/private/videos/' . $filename);

        // تشفير الفيديو قبل تخزينه
        $videoContent = file_get_contents($file->getRealPath());
        $encryptedContent = openssl_encrypt($videoContent, 'AES-256-CBC', env('ENCRYPTION_KEY'), 0, '1234567890123456');

        file_put_contents($path, $encryptedContent);

        // تحديث مسار الفيديو في قاعدة البيانات
        $data['video'] = $filename;

    }
    // تحديث بيانات المنتج
    $CoursesOfInstructorUpdate->update($data);

    return response()->json([
        'message' => 'Product updated successfully',
        'data'    => $CoursesOfInstructorUpdate,
    ]);
}




    public function searchInstructors(Request $request)
    {
        $course = CoursesOfInstructor::with('instructor')->find($request->course_id);


        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found',
            ], 404);
        }


        $instructor = $course->instructor;

        return response()->json([
            'status' => true,
            'instructor' => $instructor ? [
                'name' => $instructor->name,
                'description' => $instructor->description,
                'image' => $instructor->image,
            ] : 'N/A',
        ], 200);
    }



    // public function Courses_And_Completed_Courses(Request $request)
    // {
    //     $AllCourses=CoursesOfInstructor::where('instructor_id',$request->instructor_id)->get();
    //     $completedCourses=CoursesOfInstructor::where('instructor_id',$request->instructor_id)
    //     ->where('is_completed',1)

    //     ->get();
    //     return response()->json(['data'=>[
    //         'AllCourses'          =>  $AllCourses,
    //         'completedCourses'    =>$completedCourses ,
    //    ] ]);
    // }


    public function addTestsToCourse(Request $request)     //show test with questions
    {
        $courseId=$request->courseId;
        $request->validate([
            'tests' => 'required|array',
            'tests.*.exam_time' => 'required|date_format:H:i',
            'tests.*.exam_date' => 'required|date',
            'tests.*.exam_duration' => 'required|date_format:H:i:s',
            'tests.*.no_of_questions' => 'required|integer',
            'tests.*.is_completed' => 'required|boolean',
            'tests.*.courses_of_instructor_id' => 'required|integer',

            'tests.*.title' => 'required|string',
            'tests.*.questions' => 'required|array',
            'tests.*.questions.*' => 'required|string',
        ]);

        $course = CoursesOfInstructor::findOrFail($courseId);

        foreach ($request->tests as $testData) {
            $testAttributes = collect($testData)->only(['courses_of_instructor_id','exam_time',
             'exam_date', 'exam_duration', 'no_of_questions','is_completed', 'title'])->toArray();

            $test = new Test($testAttributes);
            $test->save();

            foreach ($testData['questions'] as $questionText) {
                $question = new Question([
                    'question_text' => $questionText,
                    'correct_answer' => $questionText,
                    'test_id' => $test->id,
                ]);
                $question->save();
            }

            // ربط الاختبار بالكورس
            $course->tests()->attach($test->id);
        }

        return response()->json(['message' => 'Tests added to course successfully.']);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $CoursesOfInstructor=CoursesOfInstructor::where('id',$id)->delete();
     return response()->json([
    'status'     => true,
    'message'    => 'Course Deleted Successfully',
],201);
}
    }

