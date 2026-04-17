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
            $table->decimal('monto_original', 12, 2)->nullable()->after('concepto');
            $table->decimal('monto_ajuste', 12, 2)->nullable()->after('monto_original');
            $table->string('tipo_ajuste')->nullable()->after('monto_ajuste'); // 'adicional', 'descuento'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_cobros', function (Blueprint $table) {
            $table->dropColumn(['monto_original', 'monto_ajuste', 'tipo_ajuste']);
        });
    }
};
