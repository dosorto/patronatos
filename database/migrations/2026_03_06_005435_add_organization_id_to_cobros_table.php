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
        if (!Schema::hasColumn('cobros', 'organization_id')) {
            Schema::table('cobros', function (Blueprint $table) {
                $table->foreignId('organization_id')->nullable()->after('id')->constrained('organizations')->onDelete('cascade');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cobros', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organization_id');
        });
    }
};
