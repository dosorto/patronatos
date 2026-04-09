<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medidores', function (Blueprint $table) {
            $table->unsignedBigInteger('miembro_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('medidores', function (Blueprint $table) {
            $table->unsignedBigInteger('miembro_id')->nullable(false)->change();
        });
    }
};
