<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Clase anónima que maneja la migración de la tabla `employees`.
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración, creando la tabla `employees`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            /**
             * ID único del empleado (clave primaria).
             *
             * @var int
             */
            $table->id();

            /**
             * ID de usuario (referencia al usuario autenticado).
             *
             * Es una clave foránea que referencia el campo `id` de la tabla `users`.
             * Si el usuario se elimina, la clave foránea se elimina automáticamente con `cascadeOnDelete()`.
             * Este campo puede ser nulo si el empleado no está asociado a un usuario.
             *
             * @var int|null
             */
            $table->foreignId('auth_id')->nullable()->constrained('users')->cascadeOnDelete();

            /**
             * ID de la compañía (referencia a la compañía del empleado).
             *
             * Es una clave foránea que referencia el campo `id` de la tabla `companies`.
             * Si la compañía se elimina, este campo será nulo debido a `nullOnDelete()`.
             *
             * @var int|null
             */
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
        });
    }

    /**
     * Revierte la migración eliminando la tabla `employees`.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
