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
        Schema::table('tbinformation', function (Blueprint $table) {
            $table->renameColumn('t_detail', 'i_detail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbinformation', function (Blueprint $table) {
            $table->renameColumn('i_detail', 't_detail');
        });
    }
};
