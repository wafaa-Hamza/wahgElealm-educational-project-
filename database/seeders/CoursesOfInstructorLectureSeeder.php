<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesOfInstructorLectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses_of_instructor_lecture')->insert([
            [
                'courses_of_instructor_id' => 1,
                'lecture_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'courses_of_instructor_id' => 1,
                'lecture_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'courses_of_instructor_id' => 2,
                'lecture_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'courses_of_instructor_id' => 2,
                'lecture_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
    }
