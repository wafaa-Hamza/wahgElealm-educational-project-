<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CoursesOfInstructor;
use App\Models\Lecture;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class lecturesadmincontroller extends Controller
{
    public function index(Request $request)
    {
    $title = $request->title;

    if ($title) {
        $Lectures = Lecture::where("title", "like", "%" . $title . "%")->get();
    } else {
        $Lectures = Lecture::all();
    }
    $lecturesData = $Lectures->map(function ($Lecture) {
        return [
            'id' => $Lecture->id,
            'video' => $Lecture->video,
            'title' => $Lecture->title,
            'created_at' => $Lecture->created_at,
            'courses' => $Lecture->courses_lectures->map(function ($course) {
                return [
                    'course_id' => $course->id,
                    'course_name' => $course->course_name,
                    'educational_level' => $course->educational_level,

                ];
            })
        ];
    });
    return response()->json(['lecturesData' => $lecturesData]);

}



public function indexData()
{
    $recentCoursesCount = Course::where('created_at', '>=', now()->subDays(30))->count();
    $recentCoursesCount = CoursesOfInstructor::where('created_at', '>=', now()->subDays(30))->count();
    $totalCoursesCount = CoursesOfInstructor::count();
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
    $new_lecture = new Lecture();
    $new_lecture->title = $request->input('title');
    $new_lecture->duration = $request->input('duration');
    $new_lecture->is_completed = $request->input('is_completed');
    $new_lecture->video = $request->input('video');

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

   $LectureStore=  Lecture::create($data);

    $new_lecture->save();

    return response()->json(['message' => 'lecture added succesfully  ']);
}

}
