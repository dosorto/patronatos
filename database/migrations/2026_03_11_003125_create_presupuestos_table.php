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
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->integer('anio_presupuesto')->nullable();
            $table->decimal('presupuesto_total', 12, 2)->nullable();
            $table->decimal('monto_financiador', 12, 2)->nullable();
            $table->decimal('monto_comunidad', 12, 2)->nullable();
            $table->decimal('porcentaje_financiador', 8, 2)->nullable();
            $table->decimal('porcentaje_comunidad', 8, 2)->nullable();
            $table->string('estado')->nullable();
            $table->date('fecha_aprobacion')->nullable();
            $table->boolean('es_donacion')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};