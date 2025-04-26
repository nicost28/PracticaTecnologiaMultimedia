<?php
// add_reviews.php
// lee JSON desde el POST
$data = json_decode(file_get_contents('php://input'), true);
$reviewsFile = 'reviews.json';

// carga el contenido actual
$all = [];
if (file_exists($reviewsFile)) {
    $all = json_decode(file_get_contents($reviewsFile), true);
    if (!is_array($all)) $all = [];
}

// añade la nueva reseña
$all[] = $data;

// guarda de nuevo con formato legible
file_put_contents($reviewsFile, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// responde Ok
header('Content-Type: application/json');
echo json_encode(['status' => 'success']);
