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
        Schema::create('subscription_in_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('courses_of_instructor_id')->constrained()->onDelete('cascade');
            $table->string('subscription_code')->unique();
            $table->string('status');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_in_courses');
    }
};
