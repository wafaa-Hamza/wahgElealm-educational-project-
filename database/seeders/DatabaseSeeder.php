<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\CoursesOfInstructorLectureSeeder;
use Database\Seeders\CoursesOfInstructorStudentSeeder;
use Database\Seeders\CoursesOfInstructorTestSeeder;
use Database\Seeders\CoursesOfInstructorUserSeeder;
use Database\Seeders\StudentStudentResultsSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            CoursesOfInstructorLectureSeeder::class,
            CoursesOfInstructorStudentSeeder::class,
            CoursesOfInstructorTestSeeder::class,
            CoursesOfInstructorUserSeeder::class,
            StudentStudentResultsSeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
