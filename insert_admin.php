<?php
require_once 'includes/db.php';

// Asegurarse de que exista la columna role
$conn->query("ALTER TABLE usuarios ADD COLUMN role VARCHAR(20) DEFAULT 'alumno'");

$username = 'admin_user';
$nombre_real = 'Administrador';
$email = 'admin@admin.com';
$password = 'admin123';
$role = 'admin';

// Encriptar la contraseña
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Verificar si ya existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Insertar solo si no existe
    $stmt = $conn->prepare("INSERT INTO usuarios (username, nombre_real, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $nombre_real, $email, $hashed_password, $role);
    if ($stmt->execute()) {
        echo "Administrador creado correctamente.";
    } else {
        echo "Error al crear el administrador.";
    }
    $stmt->close();
} else {
    echo "El usuario admin_user ya existe.";
}
?>
