<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Clase anónima que maneja la migración de la tabla `issues`.
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración, creando la tabla `issues`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('issues', function (Blueprint $table) {
            /**
             * ID único del problema (clave primaria).
             *
             * @var int
             */
            $table->id();

            /**
             * ID de la póliza de seguro asociada a este problema.
             *
             * Es una clave foránea que hace referencia al campo `id` de la tabla `insurances`.
             * Si la póliza de seguro asociada se elimina, la clave foránea se establece en `null` con `nullOnDelete()`.
             * Este campo puede ser nulo si el problema no está asociado a una póliza de seguro.
             *
             * @var int|null
             */
            $table->foreignId('insurance_id')->nullable()->constrained('insurances')->nullOnDelete();

            /**
             * Asunto o tema relacionado con el problema.
             *
             * Este campo es de tipo `text` y describe el problema.
             * No puede ser nulo.
             *
             * @var string
             */
            $table->text('subject');

            /**
             * Estado del problema.
             *
             * Este campo es de tipo `enum` y puede tener tres valores posibles:
             * - 'Abierta': El problema está abierto y en proceso.
             * - 'Cerrada': El problema ha sido resuelto y cerrado.
             * - 'Pendiente': El problema está pendiente de atención.
             * El valor predeterminado es 'Pendiente'.
             *
             * @var string
             */
            $table->enum('status', ['Abierta', 'Cerrada', 'Pendiente'])->default('Pendiente');

            /**
             * Timestamps que indican la fecha y hora de creación y actualización.
             * Estos campos son generados automáticamente por Laravel.
             *
             * @var \Carbon\Carbon|null
             */
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración eliminando la tabla `issues`.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
