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
        Schema::create('verification_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')->constrained()->onDelete('cascade');
            $table->string('omang_photo_path');
            $table->string('selfie_photo_path');
            $table->decimal('similarity_score', 5, 2);
            $table->string('ip_address');
            $table->text('user_agent');
            $table->json('device_details')->nullable();
            $table->json('face_match_details')->nullable();
            $table->json('location_details')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_sessions');
    }
};
