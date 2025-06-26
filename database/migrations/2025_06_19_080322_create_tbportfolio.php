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
        Schema::create('tbportfolio', function (Blueprint $table) {
            $table->id('p_id');
            $table->string('p_title', 255)->nullable();
            $table->string('p_category', 255)->nullable();
            $table->unsignedBigInteger('p_img')->nullable();
            $table->foreign('p_img')->references('image_id')->on('tbimage');
            $table->text('p_detail')->nullable();
            $table->integer('p_order')->nullable();
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
        Schema::dropIfExists('tbportfolio');
    }
};
