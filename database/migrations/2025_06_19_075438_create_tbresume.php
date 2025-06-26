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
        Schema::create('tbresume', function (Blueprint $table) {
            $table->id('r_id');
            $table->string('r_title', 255)->nullable();
            $table->string('r_duration', 100)->nullable();
            $table->text('r_detail')->nullable();
            $table->integer('r_type')->nullable();
            $table->tinyInteger('display')->default(0);
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbresume');
    }
};
