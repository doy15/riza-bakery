<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('production_datas', function (Blueprint $table) {
            $table->date('date')->after('shift_id'); // Tambahkan setelah kolom 'id', bisa diubah sesuai kebutuhan
        });
    }

    public function down(): void
    {
        Schema::table('production_datas', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
};
