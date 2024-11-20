<?php
session_start();
require_once 'db_connect.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

// Obtener detalles del usuario
$stmt = $pdo->prepare("
    SELECT c.cNombre as nombre, c.cApellido as apellido, c.cEmail as email 
    FROM TCliente c 
    WHERE c.nUsuarioID = ?
");
$stmt->execute([$_SESSION['user_id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    echo json_encode($usuario);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Usuario no encontrado']);
}
