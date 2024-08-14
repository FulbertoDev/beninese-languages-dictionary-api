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
        Schema::create('expressions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('word_id')->constrained();
            $table->text('inFrench');
            $table->text('inFongbe');
            $table->text('inYoruba')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expressions');
    }
};