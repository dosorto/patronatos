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
        if (!Schema::hasColumn('medidores', 'servicio_id')) {
            Schema::table('medidores', function (Blueprint $table) {
                $table->foreignId('servicio_id')->nullable()->after('miembro_id')->constrained('servicios')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medidores', function (Blueprint $table) {
            $table->dropConstrainedForeignId('servicio_id');
        });
    }
};
