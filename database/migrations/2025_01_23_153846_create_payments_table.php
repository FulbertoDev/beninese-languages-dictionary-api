<?php

use App\Helpers\PaymentStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('first_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->string('contact')->nullable(false);
            $table->string('transactionId')->nullable();
            $table->string('deviceUuid')->nullable(false);
            $table->integer('amount')->nullable(false);
            $table->enum('status', array_column(PaymentStatusEnum::cases(), 'value'))->default(PaymentStatusEnum::PENDING);
            $table->timestamps();
            $table->foreign('deviceUuid')->references('id')->on('installations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
