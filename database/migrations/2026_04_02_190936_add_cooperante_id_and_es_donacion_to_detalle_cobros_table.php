<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_cobros', function (Blueprint $table) {
            $table->unsignedBigInteger('id_cooperante')
                ->nullable()
                ->after('id');

            $table->foreign('id_cooperante')
                ->references('id_cooperante')
                ->on('cooperantes')
                ->nullOnDelete();

            $table->boolean('es_donacion')
                ->default(false)
                ->after('id_cooperante');
        });
    }

    public function down(): void
    {
        Schema::table('detalle_cobros', function (Blueprint $table) {
            $table->dropForeign(['id_cooperante']);
            $table->dropColumn('id_cooperante');
            $table->dropColumn('es_donacion');
        });
    }
};