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
        Schema::create('pending_expressions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('expression_id')->constrained();
            $table->text('inFrench')->nullable();
            $table->text('inFongbe')->nullable();
            $table->text('inYoruba')->nullable();
            $table->text('inBariba')->nullable();
            $table->text('inAdja')->nullable();
            $table->text('inBatonou')->nullable();
            $table->text('inDendi')->nullable();
            $table->text('inDitamari')->nullable();
            $table->text('inFulfulde')->nullable();
            $table->text('inGengbe')->nullable();
            $table->text('inGungbe')->nullable();
            $table->text('inYom')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_expressions');
    }
};
