<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->boolean('customer_confirmation')->after('approved_amount')->nullable;
        });
    }

    public function down()
    {
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn('customer_confirmation');
        });
    }
};
