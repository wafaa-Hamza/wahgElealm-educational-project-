<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    //  ا\\\\\\\\\\\\\\\\\\\\\الطلاب المشتركين ف الكورس
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {       //  ا\\\\\\\\\\الطلاب المشتركين ف الكورس

            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('class');
            $table->string('devices_used');
            $table->text('image');
            $table->text('no_of_courses');
            $table->date('day');
         //   $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('answer_id')->constrained('correct_answers')->onDelete('cascade');
            $table->foreignId('courses_of_instructor_id')->constrained()->onDelete('cascade');

          //  $table->foreignId('answer_id')->constrained()->onDelete('cascade');
          ///  $table->foreignId('question_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
