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
        Schema::create('accrued_benefits', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code');
            $table->float('Principal_amount')->default(0.0);
            $table->float('interest_rate')->default(0.0);
            $table->float('interest_amount')->default(0.0);
            $table->float('sub_total_amount')->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accrued_benefits');
    }
};
