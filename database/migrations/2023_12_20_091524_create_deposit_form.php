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
        Schema::create('deposit_form', function (Blueprint $table) {
            $table->id();
            $table->string('paymentAmount');
            $table->string('reciept_url')->nullable();
            $table->string('paymentType');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('application_status');
            $table->string('approval_status');
            $table->string('employee_code');
            $table->string('reciept_number')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_form');
    }
};
