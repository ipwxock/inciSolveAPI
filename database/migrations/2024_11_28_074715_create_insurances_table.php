<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Clase anónima que maneja la migración de la tabla `insurances`.
 */
return new class extends Migration
{
    /**
     * Ejecuta la migración, creando la tabla `insurances`.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('insurances', function (Blueprint $table) {
            /**
             * ID único de la póliza de seguro (clave primaria).
             *
             * @var int
             */
            $table->id();

            /**
             * Tipo de seguro o póliza.
             *
             * Este campo es de tipo `enum` que especifica los diferentes tipos de seguros posibles.
             * Los valores posibles son:
             * 'Vida', 'Robo', 'Defunción', 'Accidente', 'Incendios',
             * 'Asistencia_carretera', 'Salud', 'Hogar', 'Coche',
             * 'Moto', 'Viaje', 'Mascotas', 'Otros'.
             *
             * @var string
             */
            $table->enum('subject_type', [
                'Vida', 'Robo', 'Defunción', 'Accidente', 'Incendios',
                'Asistencia_carretera', 'Salud', 'Hogar', 'Coche',
                'Moto', 'Viaje', 'Mascotas', 'Otros'
            ]);

            /**
             * Descripción de la póliza de seguro.
             *
             * Este campo es de tipo `text` y describe los detalles del seguro.
             * No puede ser nulo.
             *
             * @var string
             */
            $table->text('description');

            /**
             * ID del cliente asociado a esta póliza.
             *
             * Es una clave foránea que referencia el campo `id` de la tabla `customers`.
             * Si el cliente se elimina, la clave foránea se establece en `null` con `nullOnDelete()`.
             * Este campo puede ser nulo si la póliza no está asociada a un cliente.
             *
             * @var int|null
             */
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();

            /**
             * ID del empleado asociado a esta póliza.
             *
             * Es una clave foránea que referencia el campo `id` de la tabla `employees`.
             * Si el empleado se elimina, la clave foránea se establece en `null` con `nullOnDelete()`.
             * Este campo puede ser nulo si la póliza no está asociada a un empleado.
             *
             * @var int|null
             */
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();

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
     * Revierte la migración eliminando la tabla `insurances`.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};
