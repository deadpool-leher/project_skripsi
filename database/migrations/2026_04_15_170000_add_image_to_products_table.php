<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'image')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('image')->nullable()->after('stok');
            });
        }

        if (Schema::hasColumn('products', 'icon') && Schema::hasColumn('products', 'image')) {
            DB::table('products')
                ->whereNull('image')
                ->update([
                    'image' => DB::raw('icon'),
                ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'image')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
