<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('score');
            $table->timestamp('processed_at')->nullable()->after('status');
            $table->softDeletes()->after('processed_at');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['status', 'processed_at']);
        });
    }
};