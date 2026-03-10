<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('medidores', 'miembro_id')) {
            Schema::table('medidores', function (Blueprint $table) {
                $table->foreignId('miembro_id')->nullable()->after('id')->constrained('miembros')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('medidores', function (Blueprint $table) {
            $table->dropConstrainedForeignId('miembro_id');
        });
    }
};