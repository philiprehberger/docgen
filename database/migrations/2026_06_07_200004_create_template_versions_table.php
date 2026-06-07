<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_versions', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->char('template_id', 26);
            $table->string('label', 16);                 // v1, v2, ...
            $table->longText('body_snapshot');
            $table->json('fields_schema');
            $table->char('created_by_api_key_id', 26)->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unique(['template_id', 'label']);
            $table->index('template_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_versions');
    }
};
