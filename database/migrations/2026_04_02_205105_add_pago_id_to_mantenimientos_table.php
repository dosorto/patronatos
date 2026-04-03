<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('mantenimientos', 'pago_id')) {
            Schema::table('mantenimientos', function (Blueprint $table) {
                $table->foreignId('pago_id')
                    ->nullable()
                    ->after('costo_estimado')
                    ->constrained('pagos')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('mantenimientos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pago_id');
        });
    }
};