<?php

require __DIR__ . "/include/bootstrap.php";

// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permitir métodos específicos
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
// Permitir encabezados específicos
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejar solicitudes OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Terminar la solicitud y devolver solo los encabezados CORS
    exit(0);
}