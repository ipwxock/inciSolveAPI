<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Clase anónima que maneja la migración de la tabla `companies`.
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración, creando la tabla `companies`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            /**
             * ID único de la empresa (clave primaria).
             *
             * @var int
             */
            $table->id();

            /**
             * Nombre de la empresa, con un máximo de 100 caracteres.
             *
             * @var string
             */
            $table->string('name', 100);

            /**
             * Descripción de la empresa. Puede ser nula.
             *
             * @var string|null
             */
            $table->text('description')->nullable();

            /**
             * Número de teléfono de la empresa. Puede ser nulo.
             *
             * @var string|null
             */
            $table->text('phone_number')->nullable();

            /**
             * Marca de tiempo de creación y actualización de la empresa.
             */
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración eliminando la tabla `companies`.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
