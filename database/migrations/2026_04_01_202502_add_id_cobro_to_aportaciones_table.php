<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aportaciones', function (Blueprint $table) {
            $table->foreignId('id_cobro')
                ->nullable()
                ->after('id_proyecto')
                ->constrained('cobros')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('aportaciones', function (Blueprint $table) {
            $table->dropForeignIdFor('cobros', 'id_cobro');
        });
    }
};