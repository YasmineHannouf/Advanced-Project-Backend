<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->integer('amount');
            $table->string('currency');
            $table->date('date_time');
            $table->boolean('is_recurring');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('category_id');
            // $table->unsignedBigInteger("author_id");
            // $table->foreign('author_id')->references("category_id")->on('categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};