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
        Schema::create('car_loans', function (Blueprint $table) {
            $table->id();
            $table->string('principal_amount');
            $table->string('application_name');
            $table->string('principal_interest');
            $table->string('monthly_repayment_amount');
            $table->string('number_of_months');
            $table->string('effective_date_of_payment')->nullable();
            // $table->string('PhoneNumber');
            // $table->string('dob');
            $table->string('w_f_no');
            $table->string('application_status');
            $table->string('approval_status');
            $table->unsignedBigInteger('application_id')->nullable();
            $table->foreign('application_id')->references('id')->on('application_types')->onDelete('cascade');
            $table->string('comment')->nullable();
            $table->float('total_loan_amount_payable')->default(0.0);
            $table->float('settled_loan_amount')->default(0.0);
            $table->float('refund_amount')->default(0.0);
            $table->string('loan_settlement_status');
            $table->float('oustanding_loan_balance')->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_loans');
    }
};
