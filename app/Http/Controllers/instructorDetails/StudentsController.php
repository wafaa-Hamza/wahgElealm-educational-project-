<?php

namespace App\Http\Controllers\instructorDetails;

use App\Http\Controllers\Controller;
use App\Http\Controllers\instructorDetails\GradesController;
use App\Models\CoursesOfInstructor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentsController extends Controller
{

        public function index(Request $request)
        {
            $students = Student::all();
            return    $students;
        }
        public function show($id)
        {
            $studentsShow = Student::findOrFail($id);
            return    $studentsShow;
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(Request $request)
        {
            $request->validate([
                'name'=>'required|max:50',
                'email' => 'required|string',
                'class' => 'required|string',
                'devices_used' => 'required|string|in:mobile,computer',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'no_of_courses' => 'required|string',
                'courses_of_instructor_id' => 'required|integer',
                'answer_id' => 'required|integer',
                'day' => 'date',
                'question_id' => 'required|integer',
            ]);
          //  dd($request->day);
            $filename = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = 'image'.time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images/', $filename);
            }


            $studentData = $request->except(['_token']);

            $studentData['image'] = $filename;
            $student = Student::create($studentData);

            if ($request->filled('course_id')) {
                $courses = CoursesOfInstructor::where('id', $request->input('course_id'))->get();
                $student->courses()->attach($courses);
                $student->load('courses');
            }
            return response()->json([
                'message' => 'Students Added Successfully',
                'data'    =>  $student ,

            ], 201);

        }
        /**
         * Display the specified resource.
         */
        // public function show(string $studentId)     //الطالب مع الدرجات والبروفايل والكورس
        // {
        //     $resultsController = new GradesController();
        //  //   return $resultsController->calculateResults($studentId);


        //     $student = Student::with(['courses.lectures', 'courses.tests'])
        //     ->findOrFail($studentId);

        //    return response()->json(['profile_and_courses' => $student,
        //    'Grades_forStudents'=>$resultsController->calculateResults($studentId)

        // ]);
        //  }


        public function getPurchasedCourses(Request $request)
        {

                $student_id = $request->student_id;

                // التحقق من صحة المدخلات
                if (empty($student_id)) {
                    return response()->json([
                        'message' => 'Student ID is required',
                    ], 400);
                }

                // التحقق مما إذا كان الطالب موجودًا
                $studentExists = DB::table('students')->where('id', $student_id)->exists();

                if (!$studentExists) {
                    return response()->json([
                        'message' => 'Student not found',
                    ], 404);
                }

                // جلب الدورات التي اشتراها الطالب
                $purchasedCourses = DB::table('courses_of_instructor_student')
                    ->join('courses_of_instructors', 'courses_of_instructor_student.courses_of_instructor_id', '=', 'courses_of_instructors.id')
                    ->where('courses_of_instructor_student.student_id', $student_id)
                    ->where('courses_of_instructors.bought', 1)
                    ->get();

                return response()->json([
                    'message' => 'Success',
                    'data' => $purchasedCourses,
                ], 200);

        }



        public function studentWithCourses($studentId)        // بروفايل الطالب مع الكورسات
        {

            // Retrieve the student along with their courses and profile
            $student = Student::with('courses')->find($studentId);

            if ($student) {
                // Return the student data with courses and profile
                return response()->json([
                    'student' => $student,
                ]);
            } else {
                return response()->json([
                    'message' => 'Student not found.',
                ], 404);
            }

        }
        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, string $id)
        {
            $request->validate([
                'name' => 'max:50',
                'email' => 'string',
                'class' => 'string',
                'devices_used' => 'string|in:mobile,computer',
                'image' => 'mimes:jpeg,png,jpg,gif|max:2048',
                'no_of_courses' => 'string',
                'courses_of_instructor_id' => 'integer',
                'answer_id' => 'integer',
                'day' => 'date',
                'question_id' => 'integer',
            ]);
            $student=Student::findOrFail($id);

           $studentData = $request->except(['image']);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = 'image'.time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images/', $filename);
                $studentData['image'] = $filename;
            }

            $student->update($studentData);
            if ($request->filled('course_id')) {
                $courses = CoursesOfInstructor::where('id', $request->input('course_id'))->get();
                $student->courses()->sync($courses);
            }


            $student->load('courses');


            return response()->json([
                'message' => 'Student Details Updated Successfully',
                'data' => $student,
            ], 200);
        }


      public function courseOfStudents_And_Subscribed(Request $request)    //بحث في الطلبه المشتركين ف الكورس
        {
            $courseId = $request->courseId;
            $course = CoursesOfInstructor::findOrFail($courseId);

            $students = $course->students()->where(function ($query) use ($request) {
                if ($request->has('name') && $request->filled('name')) {
                    $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
                }

                if ($request->has('email') && $request->filled('email')) {
                    $query->where('email', 'LIKE', '%' . $request->input('email') . '%');

                }
            })->get();

            return response()->json([
                'subscribed_students' => $students,
            ], 200);
        }


        /**
         * Remove the specified resource from storage.
         */


         public function Search_in_students(Request $request)       //بحث ف كل الطلبه
        {
            $search = $request->name;
            // $search = $request->name;
            $students = Student::when($search, function ($query) use ($search) {

                $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%');

            })->get();


            return response()->json([
                'students' => $students,
            ], 200);
        }




        /**
         * Remove the specified resource from storage.
         */
        public function destroy(string $id)
        {

           $studentDestroy=Student::where('id',$id)->delete();
          // return  $studentDestroy;
            return response()->json([
          'status'     => true,
          'message'    => 'StufentData Deleted Successfully',
      ],201);
    }
        }

