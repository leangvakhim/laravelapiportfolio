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
            $table->integer('i_type')->nullable()->after('i_img');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbinformation', function (Blueprint $table) {
            $table->dropColumn('i_type');
        });
    }
};
