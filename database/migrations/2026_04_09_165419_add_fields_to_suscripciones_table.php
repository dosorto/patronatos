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
        Schema::table('suscripciones', function (Blueprint $table) {
            $table->foreignId('medidor_id')->nullable()->after('servicio_id')->constrained('medidores')->nullOnDelete();
            $table->string('identificador')->nullable()->after('medidor_id')->comment('Nombre de la propiedad o casa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suscripciones', function (Blueprint $table) {
            $table->dropForeign(['medidor_id']);
            $table->dropColumn(['medidor_id', 'identificador']);
        });
    }
};
