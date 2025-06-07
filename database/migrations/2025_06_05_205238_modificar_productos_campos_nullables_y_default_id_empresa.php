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
        Schema::table('productos', function (Blueprint $table) {
            // Cambiar id_empresa para que tenga valor por defecto 12
            $table->integer('id_empresa')->default(12)->change();

            // Hacer nullable los campos indicados
            $table->date('ultima_salida')->nullable()->change();
            $table->char('usar_barra', 1)->nullable()->default(null)->change();
            $table->char('usar_multiprecio', 1)->nullable()->default(null)->change();

            $table->double('precio2')->nullable()->default(null)->change();
            $table->double('precio3')->nullable()->default(null)->change();
            $table->double('precio4')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Revertir cambios
            $table->integer('id_empresa')->default(null)->change();

            $table->date('ultima_salida')->nullable(false)->change();
            $table->char('usar_barra', 1)->nullable(false)->default('0')->change();
            $table->char('usar_multiprecio', 1)->nullable(false)->default('0')->change();

            $table->double('precio2')->nullable(false)->default(0)->change();
            $table->double('precio3')->nullable(false)->default(0)->change();
            $table->double('precio4')->nullable(false)->default(0)->change();
        });
    }
};
