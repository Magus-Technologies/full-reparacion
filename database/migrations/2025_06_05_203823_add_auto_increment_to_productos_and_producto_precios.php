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
        // ⚠️ Para modificar AUTO_INCREMENT usamos SQL puro (no Blueprint)
        DB::statement('ALTER TABLE productos MODIFY id_producto INT AUTO_INCREMENT');

        DB::statement('ALTER TABLE producto_precios MODIFY id INT AUTO_INCREMENT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ❗ Importante: esto elimina el AUTO_INCREMENT si haces rollback
        DB::statement('ALTER TABLE productos MODIFY id_producto INT');

        DB::statement('ALTER TABLE producto_precios MODIFY id INT');
    }
};
