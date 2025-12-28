<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alumni_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('jenis_aktivitas', [
                'organisasi',
                'seminar_workshop',
                'kepanitiaan',
                'lomba_kompetisi',
                'magang_ppl',
                'pengabdian_masyarakat',
                'keagamaan',
                'lainnya'
            ]);

            $table->string('nama_aktivitas');
            $table->year('tahun');
            $table->string('bukti_file');
            $table->enum('status', [
                'diajukan',
                'disetujui',
                'ditolak',
            ])->default('diajukan');
            $table->boolean('validasi')->default(false);
            $table->boolean('konfirmasi')->default(false);
            $table->text('catatan_revisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni_activities');
    }
};
