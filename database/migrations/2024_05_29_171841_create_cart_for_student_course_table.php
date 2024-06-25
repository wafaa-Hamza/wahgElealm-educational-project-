<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_for_student_course', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_for_student_id')->references('id')->on('cart_for_students')->onDelete('cascade');
            $table->foreignId('courses_of_instructor_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_for_student_course');
    }
};
