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
        if (!Schema::hasColumn('detalle_presupuestos', 'presupuesto_id')) {
            Schema::table('detalle_presupuestos', function (Blueprint $table) {
                $table->foreignId('presupuesto_id')->nullable()->after('id')->constrained('presupuestos')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_presupuestos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('presupuesto_id');
        });
    }
};
