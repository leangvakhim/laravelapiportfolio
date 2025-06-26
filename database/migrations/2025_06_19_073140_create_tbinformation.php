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
        Schema::create('tbinformation', function (Blueprint $table) {
            $table->id("i_id");
            $table->unsignedBigInteger('i_img')->nullable();
            $table->foreign('i_img')->references('image_id')->on('tbimage');
            $table->string('i_title', 255)->nullable();
            $table->text('t_detail')->nullable();
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
        Schema::dropIfExists('tbinformation');
    }
};
