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
        Schema::table('miembros', function (Blueprint $table) {
            // Eliminar columna municipio_id
            if (Schema::hasColumn('miembros', 'municipio_id')) {
                $table->dropForeign(['municipio_id']);
                $table->dropColumn('municipio_id');
            }

            // Eliminar FK vieja de organacion_id antes de renombrar
            if (Schema::hasColumn('miembros', 'organizacion_id')) {
                $table->dropForeign(['organizacion_id']); // <-- nombre viejo
                $table->renameColumn('organizacion_id', 'organization_id');
                $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
                    ->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            //
        });
    }
};
