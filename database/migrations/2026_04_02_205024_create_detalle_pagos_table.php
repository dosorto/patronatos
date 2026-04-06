<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_pagos', function (Blueprint $table) {
            $table->id();

            // Relación principal
            $table->foreignId('pago_id')->constrained('pagos')->cascadeOnDelete();

            // Tipo de detalle
            $table->string('tipo_detalle'); 
            // salario | mantenimiento | otro_pago

            // Relaciones opcionales
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->nullOnDelete();
            $table->foreignId('mantenimiento_id')->nullable()->constrained('mantenimientos')->nullOnDelete();

            // Información del pago
            $table->string('concepto');
            $table->text('descripcion')->nullable();
            $table->decimal('monto', 12, 2);

            // Opcional (para salario mensual, etc.)
            $table->string('periodo')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_pagos');
    }
};