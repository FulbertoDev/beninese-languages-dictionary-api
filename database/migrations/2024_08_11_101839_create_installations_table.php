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
        Schema::create('installations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->text('identifier')->nullable();
            $table->text('model')->nullable();
            $table->text('systemName')->nullable();
            $table->text('systemVersion')->nullable();
            $table->text('brand')->nullable();
            $table->boolean('hasSubscribed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installations');
    }
};
