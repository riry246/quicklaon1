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
        Schema::create('case_follow_ups_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->timestamp('follow_up_date');
            $table->unsignedBigInteger('follow_up_by');
            $table->text('notes');
            $table->enum('methods', ['Phone Call', 'Mail', 'SMS / Text Messages'])->nullable;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_follow_ups_history_tabl');
    }
};
