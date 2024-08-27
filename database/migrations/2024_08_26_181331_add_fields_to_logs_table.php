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
        Schema::table('logs', function (Blueprint $table) {
            $table->string('user', 50)->nullable();
            $table->text('file_name')->nullable();
            $table->smallInteger('line_number')->nullable()->default(0);
            $table->string('check_point', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dropColumn('user');
            $table->dropColumn('file_name');
            $table->dropColumn('line_number');
            $table->dropColumn('check_point');
        });
    }
};
