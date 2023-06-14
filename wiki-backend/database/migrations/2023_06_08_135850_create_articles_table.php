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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('space_id');
            $table->unsignedBigInteger('manual_id');
            $table->string('title');
            $table->longText('content');

            // Add other article attributes as needed
            $table->timestamps();
            $table->foreign('space_id')->references('id')->on('spaces')->onDelete('cascade');
            $table->foreign('manual_id')->references('id')->on('manuals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
