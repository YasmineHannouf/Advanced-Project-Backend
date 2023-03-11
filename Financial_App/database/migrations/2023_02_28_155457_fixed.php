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
        Schema::create('fixes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('date_time');
            $table->boolean('is_paid')->default(false);
            $table->enum('type', ['inc', 'exp']);
            $table->enum('scheduled_date',['year', 'month', 'week', 'day','hour', 'minute', 'second']);


            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->unsignedBigInteger('key_id');
            $table->foreign('key_id')->references('id')->on('fixedKeys')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixes');
    }
};