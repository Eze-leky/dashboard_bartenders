<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Obtener todos los usuarios para el selector
$usuarios = $conn->query("SELECT id, nombre_real, grupo FROM usuarios ORDER BY nombre_real ASC");

// Verificar si el usuario es administrador
if (!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Acción no autorizada']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id']) && (isset($_FILES['manual']) || isset($_FILES['diploma']))) {
        $user_id = $_POST['user_id'];
        $manual = isset($_FILES['manual']) ? $_FILES['manual'] : null;
        $diploma = isset($_FILES['diploma']) ? $_FILES['diploma'] : null;

        // Verificar si el usuario tiene grupo asignado
        $checkGrupo = $conn->prepare("SELECT grupo FROM usuarios WHERE id = ?");
        $checkGrupo->bind_param("i", $user_id);
        $checkGrupo->execute();
        $result = $checkGrupo->get_result();
        $userData = $result->fetch_assoc();

        if (is_null($userData['grupo'])) {
            echo json_encode(['success' => false, 'message' => 'El usuario no tiene un grupo asignado.']);
            exit();
        }

        // Inicializar variables para errores
        $error = null;

        // Verificar y subir el manual
        if ($manual && $manual['error'] === UPLOAD_ERR_OK) {
            if ($manual['type'] !== 'application/pdf') {
                $error = "El manual debe ser un archivo PDF.";
            } else {
                $manual_path = 'assets/pdfs/manuales/' . uniqid() . '-' . basename($manual['name']);
                $destino_manual = '../' . $manual_path;
                if (!move_uploaded_file($manual['tmp_name'], $destino_manual)) {
                    $error = "Error al subir el manual. Verifica permisos y ruta.";
                }
            }
        } else {
            $manual_path = null;
        }

        // Verificar y subir el diploma
        if ($diploma && $diploma['error'] === UPLOAD_ERR_OK) {
            if ($diploma['type'] !== 'application/pdf') {
                $error = "El diploma debe ser un archivo PDF.";
            } else {
                $diploma_path = 'assets/pdfs/diplomas/' . uniqid() . '-' . basename($diploma['name']);
                $destino_diploma = '../' . $diploma_path;
                if (!move_uploaded_file($diploma['tmp_name'], $destino_diploma)) {
                    $error = "Error al subir el diploma. Verifica permisos y ruta.";
                }
            }
        } else {
            $diploma_path = null;
        }

        // Si no hay errores, actualizar la base de datos
        if (!$error) {
            if ($manual_path && $diploma_path) {
                $stmt = $conn->prepare("UPDATE usuarios SET manual_path = ?, diploma_path = ? WHERE id = ?");
                $stmt->bind_param("ssi", $manual_path, $diploma_path, $user_id);
            } elseif ($manual_path) {
                $stmt = $conn->prepare("UPDATE usuarios SET manual_path = ? WHERE id = ?");
                $stmt->bind_param("si", $manual_path, $user_id);
            } elseif ($diploma_path) {
                $stmt = $conn->prepare("UPDATE usuarios SET diploma_path = ? WHERE id = ?");
                $stmt->bind_param("si", $diploma_path, $user_id);
            }

            if (isset($stmt)) {
                $stmt->execute();
            }

            echo json_encode(['success' => true, 'message' => 'Archivo(s) subido(s) con éxito.']);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => $error]);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Por favor, selecciona un archivo para subir.']);
        exit();
    }
}
?>