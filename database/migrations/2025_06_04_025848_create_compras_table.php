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
        Schema::create('compras', function (Blueprint $table) {
            $table->integer('id_compra')->primary();
            $table->integer('id_tido')->nullable();
            $table->integer('id_tipo_pago')->nullable();
            $table->integer('id_proveedor')->nullable();
            $table->string('fecha_emision', 50)->nullable();
            $table->string('fecha_vencimiento', 50)->nullable();
            $table->string('dias_pagos', 100)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('serie', 50)->nullable();
            $table->string('numero', 50)->nullable();
            $table->string('total', 50)->nullable();
            $table->integer('id_empresa')->nullable();
            $table->char('moneda', 1)->nullable();
            $table->integer('sucursal')->nullable();
            $table->integer('id_usuario')->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_spanish_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
