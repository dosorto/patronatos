<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'id_tipo_organizacion')) {
                $table->unsignedBigInteger('id_tipo_organizacion')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('organizations', 'id_municipio')) {
                $table->unsignedBigInteger('id_municipio')->nullable()->after('id_tipo_organizacion');
            }
            if (!Schema::hasColumn('organizations', 'id_departamento')) {
                $table->unsignedBigInteger('id_departamento')->nullable()->after('id_municipio');
            }
            if (!Schema::hasColumn('organizations', 'direccion')) {
                $table->string('direccion')->nullable()->after('id_departamento');
            }
            if (!Schema::hasColumn('organizations', 'rtn')) {
                $table->string('rtn')->nullable()->after('direccion');
            }
            if (!Schema::hasColumn('organizations', 'telefono')) {
                $table->string('telefono')->nullable()->after('rtn');
            }
            if (!Schema::hasColumn('organizations', 'fecha_creacion')) {
                $table->date('fecha_creacion')->nullable()->after('telefono');
            }
            if (!Schema::hasColumn('organizations', 'estado')) {
                $table->string('estado')->default('Activo')->after('fecha_creacion');
            }

            // Foreign keys solo si la columna existe
            if (Schema::hasColumn('organizations', 'id_tipo_organizacion')) {
                $table->foreign('id_tipo_organizacion')
                    ->references('id_tipo_organizacion')
                    ->on('tipo_organizacion')
                    ->nullOnDelete();
            }
            if (Schema::hasColumn('organizations', 'id_municipio')) {
                $table->foreign('id_municipio')
                    ->references('id')
                    ->on('municipios')
                    ->nullOnDelete();
            }
            if (Schema::hasColumn('organizations', 'id_departamento')) {
                $table->foreign('id_departamento')
                    ->references('id')
                    ->on('departamentos')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['id_tipo_organizacion']);
            $table->dropForeign(['id_municipio']);
            $table->dropForeign(['id_departamento']);

            $table->dropColumn([
                'id_tipo_organizacion',
                'id_municipio',
                'id_departamento',
                'direccion',
                'rtn',
                'telefono',
                'fecha_creacion',
                'estado',
            ]);
        });
    }
};