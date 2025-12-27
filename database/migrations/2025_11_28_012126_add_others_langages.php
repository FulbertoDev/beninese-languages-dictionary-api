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
        Schema::table('words', function (Blueprint $table) {
            $table->after('inYoruba', function (Blueprint $table) {
                $table->text('inBariba')->nullable();
                $table->text('inAdja')->nullable();
                $table->text('inBatonou')->nullable();
                $table->text('inDendi')->nullable();
                $table->text('inDitamari')->nullable();
                $table->text('inFulfulde')->nullable();
                $table->text('inGengbe')->nullable();
                $table->text('inGungbe')->nullable();
                $table->text('inYom')->nullable();
            });
        });

        Schema::table('expressions', function (Blueprint $table) {
            $table->after('inYoruba', function (Blueprint $table) {
                $table->text('inBariba')->nullable();
                $table->text('inAdja')->nullable();
                $table->text('inBatonou')->nullable();
                $table->text('inDendi')->nullable();
                $table->text('inDitamari')->nullable();
                $table->text('inFulfulde')->nullable();
                $table->text('inGengbe')->nullable();
                $table->text('inGungbe')->nullable();
                $table->text('inYom')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
