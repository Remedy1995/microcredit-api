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
        Schema::create('early_settlement_form', function (Blueprint $table) {
            $table->id();
            $table->string('type_of_loan_taken')->nullable();
            $table->float('amount_paid')->default(0.0);
            $table->string('month')->nullable();
            $table->float('balance')->default(0.0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('school_fees_detail_id')->nullable();
            $table->foreign('school_fees_detail_id')->references('id')->on('school_fees_loan_application')->onDelete('cascade');
            $table->unsignedBigInteger('happy_birthday_detail_id')->nullable();
            $table->foreign('happy_birthday_detail_id')->references('id')->on('happy_birthday_loan')->onDelete('cascade');
            $table->unsignedBigInteger('loan_detail_id')->nullable();
            $table->foreign('loan_detail_id')->references('id')->on('loan_application')->onDelete('cascade');
            $table->unsignedBigInteger('founders_day_detail_id')->nullable();
            $table->foreign('founders_day_detail_id')->references('id')->on('founders_day_loan_application')->onDelete('cascade');
            $table->unsignedBigInteger('car_detail_id')->nullable();
            $table->foreign('car_detail_id')->references('id')->on('car_loans')->onDelete('cascade');
            $table->unsignedBigInteger('christmas_detail_id')->nullable();
            $table->foreign('christmas_detail_id')->references('id')->on('christmas_loan')->onDelete('cascade');
            $table->unsignedBigInteger('easter_detail_id')->nullable();
            $table->foreign('easter_detail_id')->references('id')->on('easter_loans')->onDelete('cascade');
            $table->unsignedBigInteger('emergency_detail_id')->nullable();
            $table->foreign('emergency_detail_id')->references('id')->on('emergency_loans')->onDelete('cascade');
            $table->unsignedBigInteger('other_detail_id')->nullable();
            $table->foreign('other_detail_id')->references('id')->on('other_loans')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('early_settlement_form');
    }
};
