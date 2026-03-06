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
        if (!Schema::hasColumn('recibos', 'pago_id')) {
            Schema::table('recibos', function (Blueprint $table) {
                $table->foreignId('pago_id')->nullable()->unique()->after('correlativo')->constrained('pagos')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recibos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pago_id');
        });
    }
};
