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
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('activo_id')->nullable()->constrained('activos')->onDelete('set null');
            $table->enum('tipo_mantenimiento', ['Correctivo', 'Preventivo', 'General'])->default('General');
            $table->text('descripcion');
            $table->string('prioridad');
            $table->date('fecha_registro');
            $table->string('estado')->default('Activo');
            $table->decimal('costo_estimado', 10, 2)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantenimientos');
    }
};
