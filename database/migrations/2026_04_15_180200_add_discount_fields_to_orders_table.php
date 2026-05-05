<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'discount_code')) {
                $table->string('discount_code')->nullable()->after('total');
            }

            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->unsignedInteger('discount_amount')->default(0)->after('discount_code');
            }

            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->unsignedInteger('subtotal')->default(0)->after('produk');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'subtotal')) {
                $table->dropColumn('subtotal');
            }

            if (Schema::hasColumn('orders', 'discount_amount')) {
                $table->dropColumn('discount_amount');
            }

            if (Schema::hasColumn('orders', 'discount_code')) {
                $table->dropColumn('discount_code');
            }
        });
    }
};
