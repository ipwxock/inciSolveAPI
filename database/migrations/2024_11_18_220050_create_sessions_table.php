<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Clase anónima que maneja la migración de la tabla `sessions`.
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración, creando la tabla `sessions`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            /**
             * ID único de la sesión.
             *
             * @var string
             */
            $table->string('id')->primary();

            /**
             * ID del usuario asociado a la sesión (opcional).
             *
             * @var int|null
             */
            $table->foreignId('user_id')->nullable()->index();

            /**
             * Dirección IP del usuario en la sesión.
             *
             * @var string|null
             */
            $table->string('ip_address', 45)->nullable();

            /**
             * Información sobre el navegador o dispositivo del usuario.
             *
             * @var string|null
             */
            $table->text('user_agent')->nullable();

            /**
             * Datos almacenados en la sesión.
             *
             * @var string
             */
            $table->longText('payload');

            /**
             * Última actividad registrada en la sesión.
             *
             * @var int
             */
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Revierte la migración eliminando la tabla `sessions`.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
