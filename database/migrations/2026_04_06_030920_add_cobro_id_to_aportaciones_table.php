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
        if (!Schema::hasColumn('aportaciones', 'cobro_id')) {
            Schema::table('aportaciones', function (Blueprint $table) {
                $table->foreignId('cobro_id')->nullable()->after('id')
                    ->constrained('cobros')->onDelete('set null');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aportaciones', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cobro_id');
        });
    }
};
