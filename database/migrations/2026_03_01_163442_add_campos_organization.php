<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tipo_organizacion')->nullable()->after('phone');
            $table->unsignedBigInteger('id_municipio')->nullable()->after('id_tipo_organizacion');
            $table->unsignedBigInteger('id_departamento')->nullable()->after('id_municipio');
            $table->string('direccion')->nullable()->after('id_departamento');
            $table->string('rtn')->nullable()->after('direccion');
            $table->string('telefono')->nullable()->after('rtn');
            $table->date('fecha_creacion')->nullable()->after('telefono');
            $table->string('estado')->default('Activo')->after('fecha_creacion');

            $table->foreign('id_tipo_organizacion')
                ->references('id_tipo_organizacion')
                ->on('tipo_organizacion')
                ->nullOnDelete();

            $table->foreign('id_municipio')
                ->references('id')
                ->on('municipios')
                ->nullOnDelete();

            $table->foreign('id_departamento')
                ->references('id')
                ->on('departamentos')
                ->nullOnDelete();
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