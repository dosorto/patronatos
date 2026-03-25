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
        Schema::create('detalle_presupuestos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->decimal('cantidad', 12, 2)->nullable();
            $table->string('unidad_medida')->nullable();
            $table->decimal('precio_unitario', 12, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->string('observaciones')->nullable();
            $table->boolean('es_donacion')->nullable()->default(false);
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
        Schema::dropIfExists('detalle_presupuestos');
    }
};
