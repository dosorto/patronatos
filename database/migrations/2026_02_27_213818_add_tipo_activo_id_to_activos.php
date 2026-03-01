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
        // Verifica si la columna no existe
        if (!Schema::hasColumn('activos', 'tipo_activo_id')) {
            Schema::table('activos', function (Blueprint $table) {
                $table->foreignId('tipo_activo_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('tipo_activos') // Apunta a la tabla tipo_activos
                      ->onDelete('set null');        // Si borran el tipo, no se borra el activo
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tipo_activo_id');
        });
    }
};