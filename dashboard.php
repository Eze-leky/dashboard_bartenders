<?php
session_start();
require_once 'includes/db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Consultar nombre, manual y certificado del usuario
$stmt = $conn->prepare("SELECT nombre_real, manual_path, diploma_path FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Alumno</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>Hola!!! <?php echo htmlspecialchars($userData['nombre_real']); ?>!</h1>

    <div class="panel">
        <div class="box">
            <h2>Manual</h2>
            <?php if (!empty($userData['manual_path'])): ?>
                <!-- Contenedor clickeable del manual -->
                <div onclick="window.open('<?php echo htmlspecialchars($userData['manual_path']); ?>', '_blank')"
                     style="width: 100%; height: 400px; border: none; cursor: pointer; position: relative; overflow: hidden; border-radius: 8px;">
                    <iframe src="<?php echo htmlspecialchars($userData['manual_path']); ?>" 
                            width="100%" height="100%" style="pointer-events: none; border: none;"></iframe>
                </div>
            <?php else: ?>
                <p>No hay manual disponible por el momento.</p>
            <?php endif; ?>
        </div>

        <div class="box">
            <h2>Certificado</h2>
            <?php if (!empty($userData['diploma_path'])): ?>
                <!-- Miniatura clickeable del certificado -->
                <div onclick="window.open('<?php echo htmlspecialchars($userData['diploma_path']); ?>', '_blank')" 
                    style="width: 100%; height: 400px; border: none; cursor: pointer; position: relative; overflow: hidden; border-radius: 8px;">
                    <iframe src="<?php echo htmlspecialchars($userData['diploma_path']); ?>" 
                            width="100%" height="100%" style="pointer-events: none; border: none;"></iframe>
                </div>
                <p style="font-size: 0.9em; color: gray;">Haz clic en la imagen para abrir el certificado.</p>
            <?php else: ?>
                <p>Aquí se encontrará tu certificado si no haces un coctel de mierda.</p>
            <?php endif; ?>
        </div>

        <!-- Nueva sección: Dejar una reseña en Google -->
        <div class="box">
            <h2>¡Déjanos tu reseña en Google!</h2>
            <p>Nos encantaría recibir tu opinión sobre nuestro curso. Tu reseña nos ayuda a crecer y mejorar.</p>
            <a href="https://g.page/r/CaR8pra0JOwKEBM/review" target="_blank">
                <button type="button" style="background-color: #4285F4; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                    Dejar Reseña
                </button>
            </a>
        </div>
    </div>

    <br>
    <form method="POST" action="includes/logout.php">
        <button type="submit">Cerrar sesión</button>
    </form>
</body>
</html>