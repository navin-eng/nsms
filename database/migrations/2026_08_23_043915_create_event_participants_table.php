<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');

            // Polymorphic-style: participant can be student or staff
            $table->string('participant_type'); // 'student' or 'staff'
            $table->unsignedBigInteger('participant_id');

            $table->timestamp('registered_at')->nullable();
            $table->enum('status', ['registered', 'attended', 'cancelled'])->default('registered');
            $table->boolean('certificate_issued')->default(false);
            $table->unsignedBigInteger('certificate_id')->nullable(); // fk to certificates
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'participant_type', 'participant_id'], 'unique_event_participant');
            $table->index(['participant_type', 'participant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_participants');
    }
};
