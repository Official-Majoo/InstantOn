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
        Schema::create('omang_verification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')->constrained()->onDelete('cascade');
            $table->string('omang_number');
            $table->json('request_payload');
            $table->json('response_payload');
            $table->boolean('is_successful');
            $table->text('error_message')->nullable();
            $table->timestamp('verification_timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('omang_verification_logs');
    }
};
