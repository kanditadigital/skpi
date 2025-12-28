<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skpi_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skpi_request_id')->constrained('skpi_requests')->cascadeOnDelete();
            $table->string('nomor_skpi')->unique();
            $table->string('pdf_path');
            $table->string('hash', 64);
            $table->timestamp('issued_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skpi_documents');
    }
};
