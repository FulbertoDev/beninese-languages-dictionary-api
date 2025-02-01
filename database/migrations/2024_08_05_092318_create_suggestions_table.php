<?php

use App\Helpers\SuggestionStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suggestions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('word_id')->nullable()->constrained();
            $table->text('name')->nullable(false);
            $table->text('email')->nullable(false);
            $table->text('contact')->nullable(false);
            $table->json('data')->nullable(false);
            $table->enum('status', array_column(SuggestionStatusEnum::cases(), 'value'))->default(SuggestionStatusEnum::PENDING);
            $table->string('deviceUuid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suggestions');
    }
};
