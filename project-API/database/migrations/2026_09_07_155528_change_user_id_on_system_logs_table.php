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
        Schema::table('system_logs', function (Blueprint $table) {

        $table->dropForeign(['user_id']);
            
        $table->foreignId('user_id')->nullable()->change();

        // bghina ndirou foreign key jdida li katdir null on delete
        $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_logs', function (Blueprint $table) {
            
            $table->dropForeign(['user_id']);

            $table->foreignId('user_id')->nullable(false)->change();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
