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
        if (!Schema::hasColumn('recibos', 'cobro_id')) {
            Schema::table('recibos', function (Blueprint $table) {
                $table->foreignId('cobro_id')->nullable()->after('pago_id')->constrained('cobros')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recibos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cobro_id');
        });
    }
};
