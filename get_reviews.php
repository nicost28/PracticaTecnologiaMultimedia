<?php
// get_reviews.php
header('Content-Type: application/json');

$restaurantId = $_GET['restaurantId']; // O como recibas el ID

$file = 'reviews.json';
$usersFile = 'users.json';

if (!file_exists($file) || !file_exists($usersFile)) {
    echo json_encode([]); // Devuelve un array vacío si no hay archivos
    exit;
}

$reviews = json_decode(file_get_contents($file), true);
$users = json_decode(file_get_contents($usersFile), true);

$result = [];
foreach ($reviews as $review) {
    if ($review['restaurantId'] == $restaurantId) {
        $user = array_filter($users, function($u) use ($review) {
            return $u['id'] == $review['userId'];
        });
        $user = !empty($user) ? reset($user) : null;

        $review['username'] = $user ? $user['username'] : 'Anónimo';
        $review['avatar'] = $user ? $user['avatar'] : null;
        $result[] = $review;
    }
}

echo json_encode($result);
?>