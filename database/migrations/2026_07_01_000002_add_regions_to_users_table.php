<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->char('province_id', 2)->nullable()->after('address');
            $table->char('regency_id', 4)->nullable()->after('province_id');
            $table->char('district_id', 6)->nullable()->after('regency_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['province_id', 'regency_id', 'district_id']);
        });
    }
};
