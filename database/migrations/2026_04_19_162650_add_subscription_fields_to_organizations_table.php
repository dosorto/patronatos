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
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('plan_name')->default('Comunitario')->after('meses_mora');
            $table->string('subscription_status')->default('active')->after('plan_name'); // active, expired, suspended
            $table->date('subscription_expires_at')->default(now()->addDays(30))->after('subscription_status');
            $table->integer('max_households')->default(300)->after('subscription_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['plan_name', 'subscription_status', 'subscription_expires_at', 'max_households']);
        });
    }
};
