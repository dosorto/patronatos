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
        if (!Schema::hasColumn('empleados', 'organization_id')) {
            Schema::table('empleados', function (Blueprint $table) {
                $table->foreignId('organization_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('organizations') 
                      ->cascadeOnDelete();
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organization_id');
        });
    }
};
