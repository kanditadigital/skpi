<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skpi_submissions', function (Blueprint $table) {
            $table->index('status');
            $table->index('submitted_at');
        });

        Schema::table('alumni_activities', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->index(['konfirmasi', 'validasi']);
        });
    }

    public function down(): void
    {
        Schema::table('skpi_submissions', function (Blueprint $table) {
            $table->dropIndex(['skpi_submissions_status_index']);
            $table->dropIndex(['skpi_submissions_submitted_at_index']);
        });

        Schema::table('alumni_activities', function (Blueprint $table) {
            $table->dropIndex(['alumni_activities_user_id_status_index']);
            $table->dropIndex(['alumni_activities_konfirmasi_validasi_index']);
        });
    }
};
