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
        if (!Schema::hasColumn('lectura_medidores', 'medidor_id')) {
            Schema::table('lectura_medidores', function (Blueprint $table) {
                $table->foreignId('medidor_id')->nullable()->after('id')->constrained('medidores')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lectura_medidores', function (Blueprint $table) {
            $table->dropConstrainedForeignId('medidor_id');
        });
    }
};
