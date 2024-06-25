<?php

namespace Database\Seeders;

use App\Models\CourseDay;
use App\Models\CoursesOfInstructor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = CoursesOfInstructor::all();

        $daysOfWeek = [
            'الأحد',
            'الإثنين',
            'الثلاثاء',
            'الأربعاء',
            'الخميس',
            'الجمعة',
            'السبت'
        ];

        foreach ($courses as $course) {
            foreach ($daysOfWeek as $day) {
                CourseDay::create([
                    'courses_of_instructor_id' => $course->id,
                    'day' => $day,
                ]);
            }
        }
    }
}

