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
        Schema::create('tbachievement', function (Blueprint $table) {
            $table->id('a_id');
            $table->unsignedBigInteger('a_img')->nullable();
            $table->foreign('a_img')->references('image_id')->on('tbimage');
            $table->integer('a_type')->nullable();
            $table->tinyInteger('display');
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbachievement');
    }
};
