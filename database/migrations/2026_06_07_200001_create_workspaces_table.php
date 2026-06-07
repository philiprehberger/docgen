<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workspaces', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->string('name');
            $table->unsignedInteger('default_signed_url_ttl_seconds')->default(3600);
            $table->unsignedInteger('max_signed_url_ttl_seconds')->default(86400);
            $table->boolean('is_sandbox')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspaces');
    }
};
