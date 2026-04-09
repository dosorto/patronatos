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
        if (!Schema::hasColumn('configuracion_aportacion', 'proyecto_id')) {
            Schema::table('configuracion_aportacion', function (Blueprint $table) {
                $table->foreignId('proyecto_id')->nullable()->after('id')
                    ->constrained('proyectos')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configuracion_aportacion', function (Blueprint $table) {
            $table->dropConstrainedForeignId('proyecto_id');
        });
    }
};