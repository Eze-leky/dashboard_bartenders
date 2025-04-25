<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';

if (!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Acción no autorizada']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    $tipo_archivo = $_POST['tipo_archivo'] ?? null; // "manual_path" o "diploma_path"

    // Registrar los datos recibidos para depuración
    error_log("Datos recibidos: user_id=$user_id, tipo_archivo=$tipo_archivo");

    // Validar que los datos sean válidos
    if (!is_numeric($user_id) || !in_array($tipo_archivo, ['manual_path', 'diploma_path'])) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        error_log("Datos inválidos: user_id=$user_id, tipo_archivo=$tipo_archivo");
        exit();
    }

    // Obtener el archivo del usuario desde la base de datos
    $query = "SELECT `$tipo_archivo` FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta']);
        error_log("Error en la preparación de la consulta SQL: " . $conn->error);
        exit();
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        error_log("Usuario no encontrado: user_id=$user_id");
        exit();
    }

    $usuario = $result->fetch_assoc();
    $archivo_path = $usuario[$tipo_archivo];

    if (!$archivo_path) {
        echo json_encode(['success' => false, 'message' => 'Archivo no encontrado']);
        error_log("Archivo no encontrado en la base de datos: tipo_archivo=$tipo_archivo, user_id=$user_id");
        exit();
    }

    // Eliminar el archivo físico si existe
    $archivo_completo = "../" . $archivo_path;
    if (file_exists($archivo_completo)) {
        if (!unlink($archivo_completo)) {
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el archivo físico']);
            error_log("No se pudo eliminar el archivo físico: $archivo_completo");
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'El archivo físico no existe']);
        error_log("El archivo físico no existe: $archivo_completo");
        exit();
    }

    // Actualizar la base de datos para eliminar la referencia al archivo
    $query = "UPDATE usuarios SET `$tipo_archivo` = NULL WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta para actualizar']);
        error_log("Error en la preparación de la consulta para actualizar: " . $conn->error);
        exit();
    }

    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => ucfirst(str_replace('_path', '', $tipo_archivo)) . ' eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la base de datos']);
        error_log("Error al actualizar la base de datos: user_id=$user_id, tipo_archivo=$tipo_archivo");
    }

    $stmt->close();
    exit();
}