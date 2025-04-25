<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $input = file_get_contents('php://input');
    $credentials = json_decode($input, true);

    if (!isset($credentials['username']) || !isset($credentials['password'])) {
        http_response_code(400);
        echo json_encode(["error" => "Faltan datos."]);
        exit;
    }

    $file = './users.json';

    if (!file_exists($file)) {
        http_response_code(404);
        echo json_encode(["error" => "No hay usuarios registrados."]);
        exit;
    }

    $users = json_decode(file_get_contents($file), true);

    foreach ($users as $user) {
        if ($user['username'] === $credentials['username'] &&
            password_verify($credentials['password'], $user['password'])) {
            http_response_code(200);
            echo json_encode(["message" => "Login exitoso", "user" => $user]);
            exit;
        }
    }

    http_response_code(401);
    echo json_encode(["error" => "Credenciales incorrectas."]);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido."]);
}
?>