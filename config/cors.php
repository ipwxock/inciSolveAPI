<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Options
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin requests. You can
    | enable or disable specific options like the allowed origins, methods, 
    | headers, etc. Configure them based on your app's needs.
    |
    */

    'paths' => ['api/*'],  // Agregar la ruta 'login' si es necesario

    'allowed_methods' => ['*'],  // Permite todos los métodos (GET, POST, etc.)

    'allowed_origins' => ['http://localhost:5173'],  // Cambiar según el puerto donde está corriendo tu frontend

    'allowed_headers' => ['*'],  // Permite todos los encabezados

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];
