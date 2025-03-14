<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Clase anónima que maneja la migración de la tabla `customers`.
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración, creando la tabla `customers`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            /**
             * ID único del cliente (clave primaria).
             *
             * @var int
             */
            $table->id();

            /**
             * ID de usuario (referencia al usuario autenticado).
             *
             * Es una clave foránea que referencia el campo `id` de la tabla `users`.
             * Si el usuario se elimina, la clave foránea se elimina automáticamente con `cascadeOnDelete()`.
             * Este campo puede ser nulo si el cliente no está asociado a un usuario.
             *
             * @var int|null
             */
            $table->foreignId('auth_id')->nullable()->constrained('users')->cascadeOnDelete();

            /**
             * Número de teléfono del cliente.
             *
             * Este campo es de tipo `string` con una longitud máxima de 9 caracteres.
             * Puede ser nulo si el cliente no tiene un número de teléfono registrado.
             *
             * @var string|null
             */
            $table->string('phone_number', 9)->nullable();

            /**
             * Dirección del cliente.
             *
             * Este campo es de tipo `text` y puede ser nulo si el cliente no tiene una dirección registrada.
             *
             * @var string|null
             */
            $table->text('address')->nullable();
        });
    }

    /**
     * Revierte la migración eliminando la tabla `customers`.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
