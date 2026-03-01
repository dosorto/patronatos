<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tipo_activos', function (Blueprint $table) {
            if (!Schema::hasColumn('tipo_activos', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('tipo_activos', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users');
            }
            if (!Schema::hasColumn('tipo_activos', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->constrained('users');
            }
            if (!Schema::hasColumn('tipo_activos', 'deleted_by')) {
                $table->foreignId('deleted_by')->nullable()->constrained('users');
            }
        });
    }

    public function down()
    {
        Schema::table('tipo_activos', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['created_by', 'updated_by', 'deleted_by']);
        });
    }
};
