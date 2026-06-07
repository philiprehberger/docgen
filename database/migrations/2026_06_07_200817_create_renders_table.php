<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('renders', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->char('workspace_id', 26);
            $table->char('template_id', 26);
            $table->char('template_version_id', 26);
            $table->string('template_version_label', 16);

            $table->string('status', 16)->default('queued');
            // queued, rendering, succeeded, failed, cancelled

            $table->json('formats_requested');
            $table->json('outputs')->nullable();

            $table->string('input_data_hash', 64)->nullable();
            $table->unsignedInteger('input_data_size_bytes')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();

            $table->string('error_code', 64)->nullable();
            $table->text('error_message')->nullable();
            $table->json('error_details')->nullable();

            $table->unsignedInteger('signed_url_ttl_seconds');

            $table->char('created_by_api_key_id', 26)->nullable();
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('workspace_id');
            $table->index('template_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('renders');
    }
};
