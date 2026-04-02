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
        if (!Schema::hasColumn('miembros', 'persona_id')) {
            Schema::table('miembros', function (Blueprint $table) {
                $table->foreignId('persona_id')->nullable()->after('id')->constrained('personas')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('miembros', function (Blueprint $table) {
            $table->dropConstrainedForeignId('persona_id');
        });
    }
};
