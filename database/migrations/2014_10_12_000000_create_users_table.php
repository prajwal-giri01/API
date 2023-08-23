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
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('employee_id')->nullable();
            $table->string('mobile')->nullable();
            $table->string('p_address')->nullable();
            $table->string('t_address')->nullable();
            $table->string('qualification')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_person_details')->nullable();
            $table->string('gender')->nullable();
            $table->dateTime('dob')->nullable();

            $table->string('religion')->nullable();
            $table->string('department')->nullable();

            $table->dateTime('office_start_time')->nullable();
            $table->dateTime('office_end_time')->nullable();
            $table->string('designation')->nullable();
            $table->string('status')->nullable();

            // $table->dateTime('report_for')->default(Carbon\Carbon::now());
            $table->dateTime('date_joined')->nullable();
            $table->dateTime('date_released')->nullable();
            $table->string('extra')->nullable();

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
