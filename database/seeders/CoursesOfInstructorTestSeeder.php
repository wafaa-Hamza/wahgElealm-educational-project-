<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesOfInstructorTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses_of_instructor_test')->insert([
            [
                'courses_of_instructor_id' => 1,
                'test_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'courses_of_instructor_id' => 1,
                'test_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'courses_of_instructor_id' => 2,
                'test_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // أضف المزيد من البيانات حسب الحاجة
        ]);
    }
    }

