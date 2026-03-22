<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->enum('plan', ['free', 'starter', 'enterprise'])->default('free')->after('description');
            $table->unsignedBigInteger('documents_processed')->default(0)->after('plan');
            $table->unsignedInteger('connector_limit_override')->nullable()->after('documents_processed');
            $table->unsignedBigInteger('document_limit_override')->nullable()->after('connector_limit_override');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['plan', 'documents_processed', 'connector_limit_override', 'document_limit_override']);
        });
    }
};
