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
        Schema::table('tbblog', function (Blueprint $table) {
            $table->dropUnique(['b_order']);
            $table->integer('b_order')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbblog', function (Blueprint $table) {
            $table->integer('b_order')->unsigned()->unique()->change();
        });
    }
};
