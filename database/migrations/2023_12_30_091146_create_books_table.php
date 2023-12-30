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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('isbn',100);
            $table->string('title', 255);
            $table->string('subtitle',255)->nullable();
            $table->string('author',255)->nullable();
            $table->dateTime('published')->nullable();
            $table->string('publisher',150)->nullable();
            $table->bigInteger('pages')->nullable();
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
