<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('idempotency_records', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->char('workspace_id', 26);
            $table->string('idempotency_key', 128);
            $table->string('request_hash', 64);
            $table->string('resource_type', 32);   // 'render' for now
            $table->char('resource_id', 26);
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->unique(['workspace_id', 'idempotency_key']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('idempotency_records');
    }
};
