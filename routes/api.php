<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\dashboard\coursesadmincontroller;
use App\Http\Controllers\dashboard\lecturesadmincontroller;
use App\Http\Controllers\dashboard\ClassesAdminController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\instructorDetails\CartForStudentController;
use App\Http\Controllers\instructorDetails\CoursesOfInstructorController;
use App\Http\Controllers\instructorDetails\GradesController;
use App\Http\Controllers\instructorDetails\InstructorController;
use App\Http\Controllers\instructorDetails\LectureController;
use App\Http\Controllers\instructorDetails\RatingController;
use App\Http\Controllers\instructorDetails\StatisticsController;
use App\Http\Controllers\instructorDetails\StudentsController;
use App\Http\Controllers\instructorDetails\SubscriptionInCourseController;
use App\Http\Controllers\instructorDetails\TestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RatingCourseController;
use App\Http\Controllers\TeacherController;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



























/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('admin/register', [AdminController::class, 'showRegisterForm'])->name('admin.register');
Route::post('admin/register', [AdminController::class, 'register'])->name('admin.register');
Route::get('admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'login']);
// Route::get('admin/students', [AdminStudentsController::class, 'index']);

Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::get('admin/home', [AdminController::class, 'index']);
    Route::post('admin/courses', [coursesadmincontroller::class, 'store']);
    Route::post('admin/courses-index', [coursesadmincontroller::class, 'index']);
    Route::delete('admin/courses-indexData', [coursesadmincontroller::class, 'indexData']);
    Route::delete('admin/courses-delete/{id}', [coursesadmincontroller::class, 'destroy']);
    Route::post('admin/lectures', [lecturesadmincontroller::class, 'index']);
    Route::get('admin/lectures-indexData', [lecturesadmincontroller::class, 'indexData']);
    Route::post('admin/lectures-store', [lecturesadmincontroller::class, 'store']);
    Route::post('admin/classes', [ClassesAdminController::class, 'index']);
Route::post('admin/classes-store', [ClassesAdminController::class, 'store']);
Route::get('admin/classes-indexData', [ClassesAdminController::class, 'indexData']);

});




          //////////////////// web App //////////////////////////////////////////////
Auth::routes();

Route::post('user-register', [LoginController::class, 'register'])
     ->middleware('checkIPCount');
Route::post('user-login', [LoginController::class, 'login'])
     ->middleware('checkIPCount');

// Route::get('password-reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
//      ->middleware('checkIPCount')->name('password.request');

Route::post('password-email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
     ->middleware('checkIPCount')->name('password.email');

Route::get('password-reset/{token}', [ResetPasswordController::class, 'showResetForm'])
     ->middleware('checkIPCount')->name('password.reset');

Route::get('password-reset', [ResetPasswordController::class, 'reset'])
     ->middleware('checkIPCount')->name('password.update');


Route::resource('courses',CourseController::class);
Route::resource('teacher',TeacherController::class);
Route::post('search',[CourseController::class,'Search_In_Courses']);
Route::resource('cart',CartController::class);

Route::middleware('auth:api')->group(function () {
    Route::post('addToCart', [CartController::class, 'addToCart']);
   Route::get('viewCart', [CartController::class, 'viewCart']);
   Route::delete('removeCourse', [CartController::class, 'removeCourse']);
   Route::delete('removeCart', [CartController::class, 'removeCart']);
   Route::post('add-to-favorites',[FavoriteController::class,'addToFavorites']);
   Route::post('remove-favorites',[FavoriteController::class,'removedFromaFavorites']);
   Route::post('getand-search-favorites',[FavoriteController::class,'get_Search_Favorites']);
});

Route::post('store-rate',[RatingCourseController::class,'storeCourse']);
Route::get('show-course-rate/{teacher_id}',[RatingCourseController::class,'showRateOfCourse']);
Route::resource('payment',PaymentController::class);
Route::post('subscribe',[PaymentController::class,'processSubscription']);
Route::post('paypal-success',[PaymentController::class,'success']);
Route::post('paypal-cancel',[PaymentController::class,'cancel']);

Route::middleware('auth:api')->group(function () {
    // Route::post('subscribe', [PaymentController::class, 'subscribe']);
    Route::get('video/{id}', [CoursesOfInstructorController::class, 'showVideo']);
    Route::get('video-lecture/{id}', [LectureController::class, 'showVideo']);
});



/////////////////////////////////////////////// API FPR INSTRUCTORS\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
Route::group(['middleware' => ['auth:api', 'checkIPCount','redirectIfAuthenticated','user_type:instructor','user_type:student']], function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('instructor/dashboard', [InstructorController::class, 'dashboard'])->name('instructor.dashboard');
    Route::get('student/dashboard', [StudentsController::class, 'dashboard'])->name('student.dashboard');



});
// Route::group(['middleware' => ['auth:api', 'redirectIfAuthenticated']], function () {
//     Route::get('/home', [HomeController::class, 'index'])->name('home');
// });

// Route::group(['middleware' => ['auth:api', 'user_type:instructor']], function () {
//     Route::get('instructor/dashboard', [InstructorController::class, 'dashboard'])->name('instructor.dashboard');
// });

// Route::group(['middleware' => ['auth:api', 'user_type:student']], function () {
//     Route::get('student/dashboard', [StudentsController::class, 'dashboard'])->name('student.dashboard');
// });




Route::post('register', [LoginController::class, 'register_for_Stu_Inst'])
     ->middleware('checkIPCount');
Route::post('login', [LoginController::class, 'Login_for_Stu_Inst'])
     ->middleware('checkIPCount');

Route::resource('details-of-inst',InstructorController::class);
Route::resource('details-of-courses-const',CoursesOfInstructorController::class);
Route::get('show/{id}',[CoursesOfInstructorController::class,'show']);
Route::get('getCourses',[CoursesOfInstructorController::class,'getCourses']);
Route::get('getCompletedCourses',[CoursesOfInstructorController::class,'getCompletedCourses']);
 Route::get('get-course-details/{id}',[CoursesOfInstructorController::class,'getCourseDetails']);
 Route::post('add-test',[CoursesOfInstructorController::class,'addTestsToCourse']);
 Route::get('show-test',[InstructorController::class,'showTests']);
 Route::post('correct-answers',[InstructorController::class,'correctAnswer']);
 Route::resource('test',TestController::class);

Route::post('calcreminingpercentage/{id}',[TestController::class,'CalcReminingPercentage']);
Route::post('store',[RatingController::class,'store']);
Route::get('show-rate/{instructor_id}',[RatingController::class,'show']);

Route::resource('lecture',LectureController::class);


/////////////// الطالب هيبحث ويضيف ف السله ///////////////////////
Route::post('search-instructors',[CoursesOfInstructorController::class,'searchInstructors']);
Route::middleware('auth:api')->group(function () {
    Route::post('add-to-cartstudent', [CartForStudentController::class, 'addCourse']);
   Route::post('remove', [CartForStudentController::class, 'removeCart']);
   Route::post('remove-course', [CartForStudentController::class, 'removeCourse']);
   Route::get('index', [CartForStudentController::class, 'index']);
   Route::get('checkout', [CartForStudentController::class, 'checkout']);
   Route::post('complete-subscription', [SubscriptionInCourseController::class, 'completeSubscription']);

});

Route::get('show-purchased-courses',[StatisticsController::class,'ShowPurchasedCourses']);
Route::get('show-subscribed-courses',[StatisticsController::class,'ShowSubscribedCourses']);
Route::get('total-sales-profits',[StatisticsController::class,'TotalSales_AndProfits']);
Route::get('completion-rate',[StatisticsController::class,'CompletionRate']);
Route::get('profits',[StatisticsController::class,'ProfitsPercentage']);

// Route::get('next',[ContenCourseController::class,'next']);
// Route::get('previous',[ContenCourseController::class,'previous']);
Route::resource('test',TestController::class);
Route::post('show-questions/{id}',[TestController::class,'showQuestions']);
Route::post('close-session',[TestController::class,'closeTestSession']);
// Route::get('completed-exam',[TestController::class,'completed_exam']);
// Route::get('next',[TestController::class,'next']);
Route::resource('grades',GradesController::class);
Route::post('passing-percentage',[GradesController::class,'PassingPercentage']);
Route::post('submit-answers',[GradesController::class,'submitAnswers']);
Route::post('calculate-results',[GradesController::class,'calculateResults']);

Route::resource('students',StudentsController::class);
Route::post('studentWithCourses/{id}',[StudentsController::class,'studentWithCourses']); // بحث عن كل الطلاب
Route::get('get-boughted-courses/{student_id}',[StudentsController::class,'getPurchasedCourses']); // بحث عن كل الطلاب
Route::post('search-in-students',[StudentsController::class,'Search_in_students']); // بحث عن كل الطلاب
Route::post('subscribed-students',[StudentsController::class,'courseOfStudents_And_Subscribed']);                //  بحث عن الطلاب المشتركين ف الكورس
Route::post('verify-subscription', [SubscriptionInCourseController::class, 'verify']);















Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
