<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('production_datas', function (Blueprint $table) {
            $table->foreignId('material_id')->nullable()->constrained()->after('shift_id');
            $table->float('plan_qty')->nullable()->after('material_id');
            $table->dropColumn('material_used');
        });
    }

    public function down(): void
    {
        Schema::table('production_datas', function (Blueprint $table) {
            $table->dropForeign(['material_id']);
            $table->dropColumn('material_id');
            $table->dropColumn('plan_qty');
            $table->float('material_used')->nullable(); // Tambahkan kembali jika di-rollback
        });
    }
};

