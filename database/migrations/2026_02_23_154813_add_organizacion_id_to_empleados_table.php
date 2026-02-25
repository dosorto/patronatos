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
        if (!Schema::hasColumn('empleados', 'organizacion_id')) {
            Schema::table('empleados', function (Blueprint $table) {
                $table->foreignId('organizacion_id')
                      ->nullable()
                      ->after('persona_id')
                      ->constrained('organizacion', 'id_organizacion') 
                      ->cascadeOnDelete();
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organizacion_id');
        });
    }
};
