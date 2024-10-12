<?php
// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");

// Permitir métodos específicos
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Permitir encabezados específicos
header("Access-Control-Allow-Headers: Content-Type, Authorization");

header('Content-Type: application/json; charset=utf-8');

require_once PROJECT_ROOT_PATH . "/model/database.php";


// Manejar solicitudes preflight
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Devuelve una respuesta 200 OK para las solicitudes preflight
    http_response_code(200);
    exit();
}

header('Content-Type: application/json');

require __DIR__ . "/include/bootstrap.php";
//require "./model/usermodel.php";



//Lee el cuerpo de la solicitud JSON
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    // Conexión a la base de datos (ajusta los valores según tu configuración)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "api_rest_schedule_app";

    // Crear un array asociativo con los datos recibidos
    $modelo = [
        'username' => $data['username'],

        'user_email' => $data['email'],
        'user_gender' => $data['gender'],
        'user_country' => $data['country'],
        'user_city' => $data['city'],
        'user_age' => $data['age'],
        'password' => $data['passwords']['pass1']
    ];


    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Establecer el charset a UTF-8
    $conn->set_charset("utf8");

    // Verificar conexión
    if ($conn->connect_error) {
        die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
    }

    // $username = $data['username'];
    // $user_email = $data['email'];
    // $user_gender = $data['gender'];
    // $user_country = $data['country'];
    // $user_city = $data['city'];
    // $user_age = $data['age'];
    // $password = $data['passwords']['pass1'];

    // $sql = "INSERT INTO users (username, user_email, user_gender, user_country, user_city, user_age, pass) 
    //     VALUES ('$username', '$user_email', '$user_gender', '$user_country', '$user_city', '$user_age', '$password')";

    $sql = "INSERT INTO users (username, user_email, user_gender, user_country, user_city, user_age, pass) 
         VALUES (?,?,?,?,?,?,?)";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die(json_encode(["error" => "Error en la preparación de la consulta: " . $conn->error]));
    }

    // Vincular los parámetros
    $stmt->bind_param(
        "sssssss",
        $modelo['username'],
        $modelo['user_email'],
        $modelo['user_gender'],
        $modelo['user_country'],
        $modelo['user_city'],
        $modelo['user_age'],
        $modelo['password']
    );

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        echo json_encode(["success" => "Nuevo registro creado exitosamente"]);
    } else {
        echo json_encode(["error" => "Error al ejecutar la consulta: " . $stmt->error]);
    }

} else {
    echo json_encode(["error" => "Datos no válidos o método de solicitud no soportado"]);
}

