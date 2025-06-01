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
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('height')->comment('Рост в см');
            $table->unsignedSmallInteger('chest')->comment('Обхват груди в см');
            $table->unsignedSmallInteger('waist')->nullable()->comment('Обхват талии в см');
            $table->unsignedSmallInteger('hips')->nullable()->comment('Обхват бедер в см');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sizes');
    }
};
