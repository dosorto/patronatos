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
        Schema::table('detalle_cobros', function (Blueprint $table) {
            $table->foreignId('mantenimiento_id')->nullable()->constrained('mantenimientos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_cobros', function (Blueprint $table) {
            $table->dropForeign(['mantenimiento_id']);
            $table->dropColumn('mantenimiento_id');
        });
    }
};
