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
        if (!Schema::hasColumn('miembros', 'municipio_id')) {
            Schema::table('miembros', function (Blueprint $table) {
                $table->foreignId('municipio_id')->nullable()->after('organization_id')->constrained('municipios')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            $table->dropConstrainedForeignId('municipio_id');
        });
    }
};
