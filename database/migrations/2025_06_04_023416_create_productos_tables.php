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
        // Tabla productos
        Schema::create('productos', function (Blueprint $table) {
            $table->integer('id_producto')->primary();
            $table->string('cod_barra', 100)->nullable();
            $table->string('nombre', 255)->nullable();
            $table->double('precio', 10, 2)->nullable();
            $table->double('costo', 10, 2)->nullable();
            $table->integer('cantidad')->nullable();
            $table->integer('iscbp')->nullable();
            $table->integer('id_empresa');
            $table->integer('sucursal')->nullable();
            $table->date('ultima_salida');
            $table->string('codsunat', 20);
            $table->char('usar_barra', 1)->default('0');
            $table->char('usar_multiprecio', 1)->default('0');
            $table->double('precio_mayor', 10, 2)->nullable();
            $table->double('precio_menor', 10, 2)->nullable();
            $table->string('razon_social', 250)->nullable();
            $table->string('ruc', 11)->nullable();
            $table->char('estado', 1)->default('1');
            $table->char('almacen', 1)->nullable();
            $table->double('precio2', 10, 2)->default(0.00);
            $table->double('precio3', 10, 2)->default(0.00);
            $table->double('precio4', 10, 2)->default(0.00);
            $table->double('precio_unidad', 10, 2)->nullable();
            $table->string('codigo', 20)->nullable();
            $table->string('imagen', 255)->nullable();
            $table->text('detalle')->nullable();
            $table->integer('categoria')->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->string('unidad', 255)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_spanish_ci';
        });

        // Tabla productos_compras
        Schema::create('productos_compras', function (Blueprint $table) {
            $table->integer('id_producto_venta')->primary();
            $table->integer('id_producto')->nullable();
            $table->integer('id_compra')->nullable();
            $table->string('cantidad', 50)->nullable();
            $table->double('precio', 10, 3)->nullable();
            $table->string('costo', 50)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_spanish_ci';
        });

        // Tabla productos_ventas
        Schema::create('productos_ventas', function (Blueprint $table) {
            $table->integer('id_producto')->primary();
            $table->integer('id_venta');
            $table->integer('cantidad');
            $table->decimal('precio', 10, 2)->nullable();
            $table->decimal('costo', 10, 2)->nullable();
            $table->char('precio_usado', 1)->nullable();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_spanish_ci';
        });

        // Tabla producto_precios
        Schema::create('producto_precios', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('id_producto');
            $table->string('nombre', 255);
            $table->double('precio', 10, 2);

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
        Schema::dropIfExists('producto_precios');
        Schema::dropIfExists('productos_ventas');
        Schema::dropIfExists('productos_compras');
        Schema::dropIfExists('productos');
    }
};
