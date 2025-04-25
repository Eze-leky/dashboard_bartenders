<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php'; // Incluir functions.php

$error = '';
$success = false; // Inicializar la variable $success antes de su uso

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = login($username, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        logAccess($user['id'], $_SERVER['REMOTE_ADDR']);

        if ($user['role'] === 'admin') {
            $_SESSION['admin_id'] = $user['id'];
            header("Location: admin/index.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    } else {
        $error = "Credenciales incorrectas.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username_register'];
    $nombre_real = $_POST['nombre_real'];
    $email = $_POST['email'];
    $password = $_POST['password_register'];

    // Verificar si el nombre de usuario ya existe en la base de datos
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Si ya existe, mostrar error
        $error = "El nombre de usuario ya está en uso. Por favor, elige otro.";
    } else {
        // Si no existe, proceder a registrar el usuario
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuarios (username, nombre_real, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $nombre_real, $email, $hashed_password);

        if ($stmt->execute()) {
            $success = true; // Establecer como true si el registro es exitoso.
        } else {
            $error = "Error al registrar el usuario. Inténtalo nuevamente.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión / Registro</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Agregar FontAwesome para el ícono del ojo -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    /* Animación para el título */
    .animated-title {
        font-size: 2rem;
        font-weight: bold;
        text-align: center;
        color: #4A90E2;
        opacity: 0;
        transform: translateY(-30px);
        animation: fadeInUp 1s forwards;
    }

    /* Estilo de fondo de la página para hacerlo más suave */
    body {
        background-color: #F7F7F7;
        font-family: Arial, sans-serif;
        color: #333;
    }

    /* Definición de la animación */
    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(-30px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Estilo para el icono del ojo */
    .eye-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #4A90E2; /* Color predeterminado para el ojo */
        transition: color 0.3s ease; /* Transición suave para el color */
    }

    .eye-icon:hover {
        color: #FF5722; /* Color cuando el usuario pasa el mouse por encima */
    }

    .eye-icon.fa-eye-slash {
        color: #FF5722; /* Color para el ojo cerrado */
    }
</style>

    <script>
        function toggleForm(formType) {
            if (formType === 'login') {
                document.getElementById('login-form').style.display = 'block';
                document.getElementById('register-form').style.display = 'none';
            } else {
                document.getElementById('login-form').style.display = 'none';
                document.getElementById('register-form').style.display = 'block';
            }
        }

        function showSuccessAlert() {
            Swal.fire({
                icon: 'success',
                title: '¡Registrado correctamente!',
                text: 'Ahora puedes iniciar sesión con tu cuenta.',
                confirmButtonText: 'Iniciar sesión',
            }).then(() => {
                window.location.href = 'index.php';
            });
        }

        // Función para mostrar/ocultar la contraseña
        function togglePassword(id) {
            var passwordField = document.getElementById(id);
            var eyeIcon = document.getElementById("eye-icon-" + id);

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>
</head>
<body>
    <div class="form-container">
        <div class="animated-title">
            ¡Bienvenido a nuestro curso!
        </div>

        <!-- Formulario de inicio de sesión -->
        <div class="login-section" id="login-form">
            <h2>Iniciar sesión</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Nombre de usuario" required>
                
                <!-- Campo de contraseña con ícono de ojo -->
                <div class="relative" style="position: relative;">
                    <input type="password" name="password" id="password" placeholder="Contraseña" required>
                    <i id="eye-icon-password" class="fa fa-eye eye-icon" onclick="togglePassword('password')"></i>
                </div>
                
                <button type="submit" name="login">Entrar</button>
            </form>
            <button onclick="toggleForm('register')" class="toggle-btn">Crear cuenta</button>
        </div>

        <!-- Formulario de registro -->
        <div class="register-section" id="register-form" style="display: none;">
            <h2>Crear cuenta</h2>
            <?php if ($success): ?>
                <script>
                    showSuccessAlert();
                </script>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username_register" placeholder="Nombre de usuario" required>
                <input type="text" name="nombre_real" placeholder="Nombre completo" required>
                <input type="email" name="email" placeholder="Correo electrónico" required>
                
                <!-- Contraseña con el mismo ícono de ojo -->
                <div class="relative" style="position: relative;">
                    <input type="password" name="password_register" id="password_register" placeholder="Contraseña" required 
                        minlength="8" 
                        pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" 
                        title="La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, un número y un carácter especial.">
                    <i id="eye-icon-password_register" class="fa fa-eye eye-icon" onclick="togglePassword('password_register')"></i>
                </div>

                <button type="submit" name="register">Registrar</button>
            </form>
            <button onclick="toggleForm('login')" class="toggle-btn">Volver al inicio de sesión</button>
        </div>
    </div>
</body>
</html>
