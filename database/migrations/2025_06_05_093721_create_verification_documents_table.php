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
        Schema::create('verification_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', [
                'omang_front', 
                'omang_back', 
                'selfie', 
                'proof_of_address', 
                'other'
            ]);
            $table->string('file_path');
            $table->string('original_filename')->nullable();
            $table->string('mime_type');
            $table->integer('file_size');
            $table->timestamp('uploaded_at');
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_documents');
    }
};
