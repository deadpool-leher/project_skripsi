<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'created_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->timestamp('created_at')->nullable()->after('longitude');
            });
        }

        DB::table('orders')
            ->whereNull('created_at')
            ->update([
                'created_at' => now(),
            ]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'created_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('created_at');
            });
        }
    }
};
