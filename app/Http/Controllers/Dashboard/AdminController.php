<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CoursesOfInstructor;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    public function showRegisterForm()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phonenumber' => 'numeric',
            'password_confirmation' => 'min:6',
            'date_of_birth' => 'date',
            'gender' => 'in:male,female',

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phonenumber' => $request->phonenumber,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
            'password_confirmation' => Hash::make($request->password_confirmation),
            'is_admin' => true,
        ]);

        Auth::login($user);
        $user = Auth::user();

        $user->api_token = Str::random(60);
        $user->save();


        return response()->json([
            'status' => true,
            'message' => 'Done Successfully',
            'user ' => $user,
            'token' =>$user->api_token,

        ], 200);

    }


    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Check if the user is an admin
            if (Auth::user()->is_admin) {
                return response()->json(['message' => 'welcome!You have admin access.']);
            } else {
                Auth::logout();
                return response()->json(['email' => 'You do not have admin access.']);
            }
        }

        return response()->json(['email' => 'Invalid credentials.']);
    }

    public function index()
    {
        $recentCoursesCount = Course::where('created_at', '>=', now()->subDays(30))->count();
        $totalCoursesCount = Course::count();
        $activeTeachersCount = Teacher::has('courses')->count();
        $totalTeachersCount = Teacher::count();
        $activeStudentsCount = Student::has('courses')->count();
        $totalStudentsCount = Student::count();

        $completedCourses = CoursesOfInstructor::where('is_completed', 1)->count();
        $totalCourses = CoursesOfInstructor::count();
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
            'recentCoursesCount' => $recentCoursesCount,
            'totalCoursesCount' => $totalCoursesCount,
            'activeTeachersCount' => $activeTeachersCount,
            'totalTeachersCount' => $totalTeachersCount,
            'activeStudentsCount' => $activeStudentsCount,
            'totalStudentsCount' => $totalStudentsCount,
           'totalSales' => $totalSales,
           'totalProfits' => $totalProfit,
            'completedCourses' => ( $completedCourses /$totalCourses) * 100,
            'ProfitsPercentage' =>( $totalProfit/ $totalSales)*100,


        ]);
    }
}

