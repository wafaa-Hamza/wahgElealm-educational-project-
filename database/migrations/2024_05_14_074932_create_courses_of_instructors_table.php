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
        Schema::create('courses_of_instructors', function (Blueprint $table) {
            $table->id();
            $table->string('course_name');
            $table->string('educational_level');
            $table->foreignId('instructor_id')->constrained()->onDelete('cascade');
            // $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->string('deviced_used');
            $table->string('duration');
            $table->decimal('reminigation')->nullable();
            $table->string('code')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->string('day');
            $table->boolean('bought');
            $table->unsignedBigInteger('subscribed_by');

            $table->boolean('is_subscriped');
            $table->boolean('is_completed');
            // $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('no_of_lectures');
            $table->string('video');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses_of_instructors');
    }
};
