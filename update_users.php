<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $input = file_get_contents('php://input');
    $user = json_decode($input, true);

    if (isset($user['username']) && isset($user['password'])) {
        $file = './users.json';

        if (!file_exists($file)) {
            file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
        }

        $users = json_decode(file_get_contents($file), true);

        foreach ($users as $existingUser) {
            if ($existingUser['username'] === $user['username']) {
                http_response_code(409);
                echo json_encode(["error" => "El usuario ya existe."]);
                exit;
            }
        }
        // Generar ID automáticamente
        $lastId = 0;
        if (!empty($users)) {
            $lastId = max(array_column($users, 'id'));
        }
        $user['id'] = $lastId + 1;

        // Cifrar la contraseña
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

        // Validar y agregar avatar si existe
        if (isset($user['avatar']) && !empty($user['avatar'])) {
            $user['avatar'] = $user['avatar']; // Base64 ya viene desde el cliente
        } else {
            $user['avatar'] = null; // O un valor por defecto
        }

        $user['registeredAt'] = date('c'); // Fecha de registro
        $users[] = $user;

        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));

        http_response_code(201);
        echo json_encode(["message" => "Usuario registrado correctamente."]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Faltan datos."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido."]);
}
?>
