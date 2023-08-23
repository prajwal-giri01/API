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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->default(Carbon\Carbon::now());
            $table->dateTime('check_in_date');
            $table->dateTime('check_out_date')->nullable();
            $table->integer('work_duration')->nullable();
            $table->integer('late_early_by')->nullable();
            $table->dateTime('office_start_time');
            $table->dateTime('office_end_time');
            $table->string('extra')->nullable();
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
