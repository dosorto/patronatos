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
        if (!Schema::connection('mysql')->hasColumn('organizations', 'dias_pago')) {
            Schema::connection('mysql')->table('organizations', function (Blueprint $table) {
                $table->integer('dias_pago')->default(30)->after('meses_mora')->comment('Día del mes límite para pagar antes de entrar en mora');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql')->table('organizations', function (Blueprint $table) {
            $table->dropColumn('dias_pago');
        });
    }
};
