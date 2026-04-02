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
        if (!Schema::hasColumn('activos', 'organization_id')) {
            Schema::table('activos', function (Blueprint $table) {
                $table->foreignId('organization_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('organizations') // Apunta a la tabla correcta
                      ->onDelete('cascade');         // Borra los activos si se borra la organización
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organization_id');
        });
    }
};