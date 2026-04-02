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
        if (!Schema::hasColumn('departamentos', 'pais_id')) {
            Schema::table('departamentos', function (Blueprint $table) {
                $table->foreignId('pais_id')->nullable()->after('nombre')->constrained('pais')->onDelete('cascade');
            });
        }
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departamentos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('departamento_id');
        });
    }
};
