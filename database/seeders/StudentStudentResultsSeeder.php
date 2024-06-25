<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentStudentResultsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('student_student_results')->insert([
            [
                'student_id' => 1,
                'student_results_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 1,
                'student_results_id' => 2,

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 2,
                'student_results_id' => 1,

                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
    }

