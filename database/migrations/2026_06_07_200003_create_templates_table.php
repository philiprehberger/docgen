<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->char('workspace_id', 26);
            $table->string('name');
            $table->string('slug');
            $table->string('description', 500)->nullable();
            $table->string('engine', 16)->default('twig');
            $table->longText('body');
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->unique(['workspace_id', 'slug']);
            $table->index('workspace_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
