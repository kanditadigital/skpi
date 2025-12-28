<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skpi_master_contents', function (Blueprint $table) {
            $table->json('kkni_items_json')->nullable()->after('special_attitude_json');
        });
    }

    public function down(): void
    {
        Schema::table('skpi_master_contents', function (Blueprint $table) {
            $table->dropColumn('kkni_items_json');
        });
    }
};
