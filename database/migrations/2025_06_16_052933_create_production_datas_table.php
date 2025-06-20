<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('production_datas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('line_id')->constrained();
            $table->foreignId('shift_id')->constrained();
            $table->time('production_start');
            $table->time('production_end');
            $table->integer('target')->default(0);
            $table->float('material_used')->nullable();
            $table->integer('ok')->default(0);
            $table->integer('ng')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_datas');
    }
};
