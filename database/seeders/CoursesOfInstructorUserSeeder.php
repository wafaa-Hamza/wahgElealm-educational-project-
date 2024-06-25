<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesOfInstructorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('courses_of_instructor_user')->insert([
        [
            'courses_of_instructor_id' => 1,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'courses_of_instructor_id' => 1,
            'user_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ],


    ]);
}
    }

