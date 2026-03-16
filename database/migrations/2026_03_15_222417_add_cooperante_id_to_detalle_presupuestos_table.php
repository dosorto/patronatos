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
        if (!Schema::hasColumn('detalle_presupuestos', 'id_cooperante')) {

            Schema::table('detalle_presupuestos', function (Blueprint $table) {

                $table->unsignedBigInteger('id_cooperante')
                    ->nullable()
                    ->after('es_donacion');

                $table->foreign('id_cooperante')
                    ->references('id_cooperante')
                    ->on('cooperantes')
                    ->onDelete('cascade');

            });
        }
    }
    
    public function down(): void
    {
        Schema::table('detalle_presupuestos', function (Blueprint $table) {

            $table->dropForeign(['id_cooperante']);
            $table->dropColumn('id_cooperante');

        });
    }
};
