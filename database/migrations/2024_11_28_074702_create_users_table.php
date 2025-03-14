<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Clase anónima que maneja la migración de la tabla `users`.
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración, creando la tabla `users`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            /**
             * ID único del usuario (clave primaria).
             *
             * @var int
             */
            $table->id();

            /**
             * Número de identificación del usuario (DNI). Es único y tiene un máximo de 20 caracteres.
             *
             * @var string
             */
            $table->string('dni', 20)->unique();

            /**
             * Primer nombre del usuario, con un máximo de 50 caracteres.
             *
             * @var string
             */
            $table->string('first_name', 50);

            /**
             * Apellido del usuario, con un máximo de 50 caracteres.
             *
             * @var string
             */
            $table->string('last_name', 50);

            /**
             * Correo electrónico del usuario. Es único y puede ser nulo.
             *
             * @var string|null
             */
            $table->string('email', 100)->unique()->nullable();

            /**
             * Contraseña del usuario. Con un máximo de 100 caracteres.
             *
             * @var string
             */
            $table->string('password', 100);

            /**
             * Rol del usuario. Puede ser uno de los siguientes valores: 'Cliente', 'Empleado', 'Manager', 'Admin'.
             *
             * @var string
             */
            $table->enum('role', ['Cliente', 'Empleado', 'Manager', 'Admin']);

            /**
             * Marca de tiempo de creación y actualización del usuario.
             */
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración eliminando la tabla `users`.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
