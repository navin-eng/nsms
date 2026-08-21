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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_no')->unique(); // e.g. CERT-2026-0001
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('type'); // transfer, character, bonafide, completion, merit, custom
            $table->string('title');
            $table->date('issue_date');
            $table->json('metadata')->nullable(); // custom fields (reason, conduct, marks, etc.)
            $table->string('qr_token', 64)->unique(); // unique token for QR code public verification
            $table->string('status')->default('issued'); // issued, revoked
            $table->text('revocation_reason')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
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
        Schema::dropIfExists('certificates');
    }
};
