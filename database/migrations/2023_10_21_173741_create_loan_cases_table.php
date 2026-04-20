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
        Schema::create('loan_cases', function (Blueprint $table) {
            $table->id();
            $table->integer('loan_application_id');
            $table->string('case_number');
            $table->string('topic');
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Low');
            $table->enum('status', ['Open', 'In Progress', 'On Hold', 'Resolved', 'Reassigned', 'Pending Customer', 'Pending Review', 'Closed', 'Escalated', 'Rejected', 'Suspended', 'Under Investigation', 'Waiting for Approval', 'Canceled'])->default('Open');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->dateTime('next_follow_up')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_cases');
    }
};
