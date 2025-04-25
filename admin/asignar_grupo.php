<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';

if (!isAdmin()) {
    // Si no es administrador, devuelve un mensaje de error en formato JSON
    echo json_encode(['success' => false, 'message' => 'Acción no autorizada']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $grupo = $_POST['grupo'];

    // Validar que los datos estén presentes
    if (!is_numeric($user_id) || !is_numeric($grupo)) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit();
    }

    // Consulta para actualizar el grupo del usuario
    $query = "UPDATE usuarios SET grupo = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $grupo, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Grupo actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el grupo']);
    }

    $stmt->close();
    exit();
}