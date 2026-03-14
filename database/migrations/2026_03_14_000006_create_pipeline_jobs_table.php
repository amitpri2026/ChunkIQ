<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Named pipeline_jobs to avoid conflict with Laravel's built-in jobs table
        Schema::create('pipeline_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('connector_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('triggered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->enum('type', ['ingestion', 'processing']);
            $table->enum('status', ['pending', 'running', 'succeeded', 'failed', 'cancelled'])->default('pending');
            $table->text('config')->nullable();  // JSON job-specific configuration
            $table->longText('logs')->nullable(); // accumulated log output
            $table->string('callback_token', 64)->unique()->nullable(); // for Function App callbacks
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pipeline_jobs');
    }
};
