<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('production_datas', function (Blueprint $table) {
            // Hapus kolom 'target'
            $table->dropColumn('target');

            // Tambahkan kolom 'cctime'
            $table->float('cctime')->nullable()->after('plan_qty');
        });
    }

    public function down(): void
    {
        Schema::table('production_datas', function (Blueprint $table) {
            // Tambahkan kembali kolom 'target' jika di-rollback
            $table->integer('target')->default(0);

            // Hapus kolom 'cctime' jika di-rollback
            $table->dropColumn('cctime');
        });
    }
};
