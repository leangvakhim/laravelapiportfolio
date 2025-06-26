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
        Schema::create('tbsocial', function (Blueprint $table) {
            $table->id('s_id');
            $table->string('s_title', 255)->nullable();
            $table->unsignedBigInteger('s_img')->nullable();
            $table->foreign('s_img')->references('image_id')->on('tbimage');
            $table->text('s_link')->nullable();
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
        Schema::dropIfExists('tbsocial');
    }
};
