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
        Schema::create('categorias', function (Blueprint $table) {
            $table->increments('id'); // id INT AUTO_INCREMENT PRIMARY KEY
            $table->string('nombre')->nullable(); // nombre VARCHAR(255) NULL
            $table->timestamp('creado_el')->useCurrent(); // timestamp con valor por defecto CURRENT_TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
