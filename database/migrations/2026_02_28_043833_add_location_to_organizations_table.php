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
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'id_pais')) {
                $table->unsignedBigInteger('id_pais')->nullable();
            }
            if (!Schema::hasColumn('organizations', 'id_departamento')) {
                $table->unsignedBigInteger('id_departamento')->nullable();
            }
            if (!Schema::hasColumn('organizations', 'id_municipio')) {
                $table->unsignedBigInteger('id_municipio')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            //
        });
    }
};
