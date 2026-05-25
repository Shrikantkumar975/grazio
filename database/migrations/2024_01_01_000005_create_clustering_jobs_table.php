<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clustering_jobs', function (Blueprint $table) {
            $table->id();

            $table->enum('status', ['pending', 'running', 'completed', 'failed'])
                  ->default('pending');

            // The user who kicked off the job
            $table->foreignId('triggered_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            // Stored if status = 'failed'
            $table->text('error_message')->nullable();

            $table->unsignedInteger('trajectories_processed')->default(0);

            $table->timestamps();

            $table->index('status');
            $table->index('triggered_by');
            $table->index('started_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clustering_jobs');
    }
};
