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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phonenumber');
            $table->string('email');
            $table->string('password');
            $table->string('password_confirmation');
            $table->string('user_type')->nullable();
            $table->date('date_of_birth');
            $table->text('gender');
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->boolean('is_admin')->default(false);


            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
