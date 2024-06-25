<?php

namespace App\Http\Controllers\instructorDetails;

use App\Http\Controllers\Controller;
use App\Models\CoursesOfInstructor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ShowCourses=CoursesOfInstructor::all();
        $CountCourses=CoursesOfInstructor::count();


    }

    public function ShowPurchasedCourses() // عدد الكورسات المباعه
    {
        $ShowCourses=CoursesOfInstructor::all();

       $purchased_courses=CoursesOfInstructor::where('bought',1)->count();
       return response()->json(['purchased_courses'=>$purchased_courses]);
    }

    public function ShowSubscribedCourses()   // عدد الطلاب المشتركين
    {
       $subscriped_courses=CoursesOfInstructor::with('students')
        ->where('is_subscribed',1)->count();
       return response()->json(['subscriped_courses'=>$subscriped_courses]);
    }

    public function  TotalSales_AndProfits()        //حساب نسبة الارباح والمبيعات للكورسات
    {

      $purchasedCourses = CoursesOfInstructor::where('bought', true)->get();
    //   return $purchasedCourses;
      $totalSales = 0;
      $Allcourses = CoursesOfInstructor::get();

      foreach ($purchasedCourses as $purchcourse) {

        $totalSales += $purchcourse->price;
      //  return   $totalSales;
        $totalProfit = 0;

         foreach ($Allcourses as $course) {

            $profit = $course->where('is_subscribed',1)->count() * $course->price;

           $totalProfit += $profit;
          // return   $totalProfit;
        }
    }
return response()->json([
    'data' => [
        'totalSales' => $totalSales,
        'totalProfits'     => $totalProfit
    ]
]);

}

    public function CompletionRate ()  // حساب الدورات المكتمله
    {
        $completedCoursesCount = CoursesOfInstructor::where('is_completed', 1)->count();
    // return  $completedCoursesCount;
    $totalCoursesCount = CoursesOfInstructor::count();

    // return  $totalCoursesCount;
        if ($completedCoursesCount > 0) {
            $completionRate = ($completedCoursesCount / $totalCoursesCount) * 100;
        } else {
            $completionRate = 0;
        }
        return response()->json(['completionRate'=>$completionRate]);
    }


public function ProfitsPercentage()  //نسبة الارباح
{

        $jsonResponse = $this->TotalSales_AndProfits();
    ///  return($Rev);
    $dataArray = $jsonResponse->getData(true);
        $totalSales =$dataArray ['data']['totalSales'];
        $totalProfit =$dataArray ['data']['totalProfits'];

        $ProfitsPercentage =( $totalProfit/ $totalSales)*100;


    return response()->json(['ProfitsPercentage'=>$ProfitsPercentage]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
