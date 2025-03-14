<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Clase anónima que maneja la migración de la tabla `personal_access_tokens`.
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración, creando la tabla `personal_access_tokens`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            /**
             * ID único del token.
             *
             * @var int
             */
            $table->id();

            /**
             * Relación polimórfica con el modelo asociado al token.
             */
            $table->morphs('tokenable');

            /**
             * Nombre del token.
             *
             * @var string
             */
            $table->string('name');

            /**
             * Valor único del token en formato hash.
             *
             * @var string
             */
            $table->string('token', 64)->unique();

            /**
             * Lista de habilidades (permisos) asociadas al token.
             *
             * @var string|null
             */
            $table->text('abilities')->nullable();

            /**
             * Última fecha y hora en la que se utilizó el token.
             *
             * @var \Illuminate\Support\Carbon|null
             */
            $table->timestamp('last_used_at')->nullable();

            /**
             * Fecha y hora de expiración del token.
             *
             * @var \Illuminate\Support\Carbon|null
             */
            $table->timestamp('expires_at')->nullable();

            /**
             * Marca de tiempo de creación y actualización del token.
             */
            $table->timestamps();
        });
    }

    /**
     * Revierte la migración eliminando la tabla `personal_access_tokens`.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
