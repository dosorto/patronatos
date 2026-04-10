<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('moras', function (Blueprint $table) {
            $table->unsignedBigInteger('suscripcion_id')->nullable()->after('estado');
            $table->unsignedBigInteger('aportacion_id')->nullable()->after('suscripcion_id');
            $table->date('mes_referencia')->nullable()->after('aportacion_id');

            $table->foreign('suscripcion_id')->references('id')->on('suscripciones')->onDelete('cascade');
            $table->foreign('aportacion_id')->references('id')->on('aportaciones')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('moras', function (Blueprint $table) {
            $table->dropForeign(['suscripcion_id']);
            $table->dropForeign(['aportacion_id']);
            $table->dropColumn(['suscripcion_id', 'aportacion_id', 'mes_referencia']);
        });
    }
};
