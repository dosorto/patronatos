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
        if (!Schema::hasColumn('asistencias_jornada', 'miembro_id')) {
            Schema::table('asistencias_jornada', function (Blueprint $table) {
                $table->foreignId('miembro_id')->nullable()->after('jornada_id')
                    ->constrained('miembros')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asistencias_jornada', function (Blueprint $table) {
            $table->dropConstrainedForeignId('miembro_id');
        });
    }
};