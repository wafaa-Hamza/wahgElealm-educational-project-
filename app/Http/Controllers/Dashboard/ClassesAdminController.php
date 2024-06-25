<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\classModel;
use App\Models\Course;
use App\Models\CoursesOfInstructor;
use App\Models\instructor;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ClassesAdminController extends Controller
{
                public function index(Request $request)
                {
                    $search_query = $request->search_query;

                    $query = classModel::with(['course', 'instructor']);

                    if ($search_query) {
                        $query->where('title', 'like', "%{$search_query}%");
                    }

                    $classes = $query->get();



            return response()->json(['classes' => $classes]);
        }


        public function indexData()
        {
            $recentCoursesCount = CoursesOfInstructor::where('created_at', '>=', now()->subDays(30))->count();
            $totalCoursesCount = CoursesOfInstructor::count();
            $activeTeachersCount = instructor::has('coursesofinstrts')->count();
            $totalTeachersCount = instructor::count();
            $pendingPayments = Payment::where('status', 0)->sum('amount');

            $paidPayments = Payment::where('status', 1)->sum('amount');



            return response()->json([
                'recentCoursesCount' => $recentCoursesCount,
                'totalCoursesCount' => $totalCoursesCount,
                'activeTeachersCount' => $activeTeachersCount,
                'totalTeachersCount' => $totalTeachersCount,
                'pendingPayments' => $pendingPayments,
                'paidPayments' => $paidPayments,
            ]);
        }




        public function store(Request $request)
        {
            $request->validate([
                'title' => 'required|string|max:255',
                'price' => 'required|numeric',
                'courses_of_instructor_id' => 'required',
                'instructor_id' => 'required|exists:instructors,id',
            ]);

            classModel::create($request->all());


        return response()->json(['message' => 'course added succesfully  ']);
    }

    public function destroy($id)
    {
     $classModelDestroy=classModel::where('id',$id)->delete();
return response()->json([
    'status'     => true,
    'message'    => 'classModel Deleted Successfully',
],201);
}
}
