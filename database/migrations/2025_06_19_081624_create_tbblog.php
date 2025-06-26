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
        Schema::create('tbblog', function (Blueprint $table) {
            $table->id('b_id');
            $table->string('b_title', 255)->nullable();
            $table->text('b_subtitle')->nullable();
            $table->text('b_detail')->nullable();
            $table->unsignedBigInteger('b_img')->nullable();
            $table->foreign('b_img')->references('image_id')->on('tbimage');
            $table->date('b_date')->nullable();
            $table->integer('b_order')->unsigned()->unique();
            $table->integer('display')->default(0);
            $table->integer('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbblog');
    }
};
