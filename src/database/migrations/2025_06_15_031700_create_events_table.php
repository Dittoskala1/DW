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
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');

        $table->date('start_date');
        $table->time('start_time');
        $table->date('end_date');
        $table->time('end_time');

        $table->string('location');
        $table->enum('status', ['draft', 'published', 'archived', 'pending'])->default('draft');

        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('poster_url')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
