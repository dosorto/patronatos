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
        if (!Schema::hasColumn('proyectos', 'miembro_responsable_id')) {
            Schema::table('proyectos', function (Blueprint $table) {
                $table->foreignId('miembro_responsable_id')->nullable()->after('numero_acta')->constrained('directivas')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('miembro_responsable_id');
        });
    }
};
