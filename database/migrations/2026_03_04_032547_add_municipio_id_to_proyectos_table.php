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
        if (!Schema::hasColumn('proyectos', 'municipio_id')) {
            Schema::table('proyectos', function (Blueprint $table) {
                $table->foreignId('municipio_id')->nullable()->after('departamento_id')->constrained('municipios')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('municipio_id');
        });
    }
};
