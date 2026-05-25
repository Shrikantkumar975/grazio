<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trajectories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('name');
            $table->string('source')->nullable()->comment('csv | json | api');

            $table->enum('status', ['pending', 'processing', 'clustered', 'failed'])
                  ->default('pending');

            $table->unsignedInteger('total_points')->default(0);
            $table->timestamp('uploaded_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('uploaded_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trajectories');
    }
};
