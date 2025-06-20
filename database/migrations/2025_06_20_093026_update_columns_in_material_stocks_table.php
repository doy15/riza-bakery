<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('material_stocks', function (Blueprint $table) {
            // Ubah jadi nullable
            $table->foreignId('line_id')->nullable()->change();
            $table->foreignId('production_data_id')->nullable()->change();

            // Ubah nama kolom 'used' menjadi 'qty'
            $table->renameColumn('used', 'qty');
        });
    }

    public function down(): void
    {
        Schema::table('material_stocks', function (Blueprint $table) {
            // Balikkan nullable
            $table->foreignId('line_id')->nullable(false)->change();
            $table->foreignId('production_data_id')->nullable(false)->change();

            // Balikkan nama kolom ke 'used'
            $table->renameColumn('qty', 'used');
        });
    }
};
