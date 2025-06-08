<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_officer_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('officer_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['approved', 'rejected', 'pending_additional_info']);
            $table->text('notes');
            $table->timestamp('review_timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_officer_reviews');
    }
};