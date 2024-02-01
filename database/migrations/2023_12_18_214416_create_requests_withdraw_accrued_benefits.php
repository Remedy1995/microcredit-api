<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requests_withdraw_accrued_benefits', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code');
            $table->float('amount_to_withdraw')->default(0.0);
            $table->string('amount_in_words');
            $table->string('application_status')->nullable();
            $table->text('comment')->nullable();
            $table->string('approval_status')->nullable();
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
        Schema::dropIfExists('requests_withdraw_accrued_benefits');
    }
};
