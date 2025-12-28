<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skpi_master_contents', function (Blueprint $table) {
            $table->id();
            $table->string('kop_surat_path')->nullable();
            $table->text('opening_text_id')->nullable();
            $table->text('opening_text_en')->nullable();
            $table->json('institution_info_json')->nullable();
            $table->json('working_capability_json')->nullable();
            $table->json('special_attitude_json')->nullable();
            $table->text('kkni_text_id')->nullable();
            $table->text('kkni_text_en')->nullable();
            $table->string('city')->nullable();
            $table->string('leader_name')->nullable();
            $table->string('leader_title')->nullable();
            $table->string('leader_nidn')->nullable();
            $table->string('leader_signature_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skpi_master_contents');
    }
};
