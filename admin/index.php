<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Verificar si el usuario es administrador
if (!isAdmin()) {
    header("Location: ../index.php");
    exit();
}

// Consulta SQL para obtener usuarios sin grupo
$querySinGrupo = "SELECT u.*, c.nombre AS nombre_curso 
                  FROM usuarios u 
                  LEFT JOIN cursos c ON u.curso_id = c.id 
                  WHERE u.grupo IS NULL 
                  ORDER BY u.anio DESC, u.mes DESC, u.curso_id ASC, 
                           FIELD(u.dias_cursada, 'Martes y Jueves', 'Miércoles y Viernes', 'Sábados')";
$usuariosSinGrupo = $conn->query($querySinGrupo);

if (!$usuariosSinGrupo) {
    die("Error en la consulta SQL para usuarios sin grupo: " . $conn->error);
}

// Función para obtener y mostrar usuarios con grupo asignado
function obtenerUsuariosConGrupo($conn, $grupoId, $nombreGrupo)
{
    $stmt = $conn->prepare(
        "SELECT u.*, c.nombre AS nombre_curso 
         FROM usuarios u 
         LEFT JOIN cursos c ON u.curso_id = c.id 
         WHERE u.grupo = ? 
         ORDER BY u.anio DESC, u.mes DESC, u.curso_id ASC, u.dias_cursada ASC"
    );

    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $grupoId);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if (!$resultado) {
        die("Error en la consulta SQL para usuarios con grupo: " . $stmt->error);
    }

    if ($resultado->num_rows > 0) :
        ?>
        <div class="mt-10">
            <button onclick="togglePanel('grupo-<?php echo $grupoId; ?>')" class="w-full text-left px-6 py-4 bg-blue-200 hover:bg-blue-300 font-semibold text-lg">
                Grupo <?php echo $grupoId; ?> - <?php echo htmlspecialchars($nombreGrupo); ?>
            </button>

            <div id="grupo-<?php echo $grupoId; ?>" class="hidden p-4 bg-blue-50 border border-blue-300 rounded-b">
                
            <?php while ($usuario = $resultado->fetch_assoc()) : ?>
    <div class="user-name" id="group-user-container-<?php echo $usuario['id']; ?>">
        <button onclick="toggleUserDetails('group-user-<?php echo $usuario['id']; ?>')" class="w-full text-left text-xl font-semibold hover:bg-gray-200 px-4 py-2">
            <?php echo htmlspecialchars($usuario['nombre_real']); ?>
        </button>

        <div id="group-user-<?php echo $usuario['id']; ?>" class="hidden p-4 bg-gray-50 border border-gray-200 rounded-md max-w-md mx-auto">
            <!-- Mostrar detalles del usuario -->
            <p>Curso: <?php echo htmlspecialchars($usuario['nombre_curso']); ?></p>
            <p>Año: <?php echo htmlspecialchars($usuario['anio']); ?>, Mes: <?php echo htmlspecialchars($usuario['mes']); ?></p>
            <p>Días de cursada: <?php echo htmlspecialchars($usuario['dias_cursada']); ?></p>

            <!-- Mostrar manual -->
            <div class="manual-container">
                <?php if ($usuario['manual_path']) : ?>
                    <div class="flex items-center gap-2">
                        <span class="text-green-500">Manual cargado: <a href="../<?php echo htmlspecialchars($usuario['manual_path']); ?>" target="_blank" class="underline">Ver manual</a></span>
                        <button class="text-red-500 hover:underline eliminar-archivo" data-user-id="<?php echo $usuario['id']; ?>" data-tipo-archivo="manual_path">Eliminar</button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Mostrar diploma -->
            <div class="diploma-container">
                <?php if ($usuario['diploma_path']) : ?>
                    <div class="flex items-center gap-2">
                        <span class="text-green-500">Diploma cargado: <a href="../<?php echo htmlspecialchars($usuario['diploma_path']); ?>" target="_blank" class="underline">Ver diploma</a></span>
                        <button class="text-red-500 hover:underline eliminar-archivo" data-user-id="<?php echo $usuario['id']; ?>" data-tipo-archivo="diploma_path">Eliminar</button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Formulario para cargar archivos -->
            <form action="upload.php" method="POST" enctype="multipart/form-data" class="mt-4 upload-form">
                <input type="hidden" name="user_id" value="<?php echo $usuario['id']; ?>">

                <div>
                    <label for="manual" class="block text-sm font-medium text-gray-700">Subir Manual (PDF):</label>
                    <input type="file" name="manual" accept=".pdf" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div class="mt-4">
                    <label for="diploma" class="block text-sm font-medium text-gray-700">Subir Diploma (PDF):</label>
                    <input type="file" name="diploma" accept=".pdf" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition mt-4">
                    Subir Archivos
                </button>
            </form>
        </div>
    </div>
<?php endwhile; ?>
            
            
            
            
            




            </div>
        </div>
    <?php endif;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Panel de Administración</h1>

        <!-- Usuarios sin grupo asignado -->
        <h2 class="text-2xl font-bold mb-4 text-center text-gray-700">Usuarios SIN grupo asignado</h2>
        <div class="panel">
            <?php
            if ($usuariosSinGrupo && $usuariosSinGrupo->num_rows > 0) {
                while ($usuario = $usuariosSinGrupo->fetch_assoc()) :
            ?>
                    <div class="user-name" id="user-container-<?php echo $usuario['id']; ?>">
                        <button onclick="toggleUserDetails('user-<?php echo $usuario['id']; ?>')" class="w-full text-left text-xl font-semibold hover:bg-gray-200 px-4 py-2">
                            <?php echo htmlspecialchars($usuario['nombre_real']); ?>
                        </button>

                        <div id="user-<?php echo $usuario['id']; ?>" class="hidden p-4 bg-gray-50 border border-gray-200 rounded-md max-w-md mx-auto">
                            <!-- Mostrar detalles del usuario -->
                            <p>Curso: <?php echo htmlspecialchars($usuario['nombre_curso']); ?></p>
                            <p>Año: <?php echo htmlspecialchars($usuario['anio']); ?>, Mes: <?php echo htmlspecialchars($usuario['mes']); ?></p>
                            <p>Días de cursada: <?php echo htmlspecialchars($usuario['dias_cursada']); ?></p>

                            <!-- Formulario para asignar grupo -->
                            <form class="assign-group mt-4" data-user-id="<?php echo $usuario['id']; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $usuario['id']; ?>">

                                <label for="grupo" class="block text-sm font-medium text-gray-700">Asignar Grupo:</label>
                                <select name="grupo" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                                    <option value="1">Martes y Jueves</option>
                                    <option value="2">Sábados</option>
                                    <option value="3">Miércoles y Viernes</option>
                                </select>

                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition mt-4">
                                    Asignar Grupo
                                </button>
                            </form>
                        </div>
                    </div>
            <?php
                endwhile;
            } else {
                echo "<p class='text-red-500'>No se encontraron usuarios sin grupo asignado.</p>";
            }
            ?>
        </div>

        <!-- Usuarios con grupo asignado -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4 text-center text-gray-700">Usuarios con grupo asignado</h2>
            <?php
            obtenerUsuariosConGrupo($conn, 1, 'Martes y Jueves');
            obtenerUsuariosConGrupo($conn, 2, 'Sábados');
            obtenerUsuariosConGrupo($conn, 3, 'Miércoles y Viernes');
            ?>
        </div>
    </div>
    <script>
        // Funcionalidad para alternar visibilidad de detalles del usuario
        function toggleUserDetails(id) {
            const userDetails = document.getElementById(id);
            if (userDetails) {
                userDetails.classList.toggle("hidden");
            }
        }

        // Funciones de asignar grupo (usuarios sin grupo) con AJAX
        document.addEventListener('DOMContentLoaded', () => {
            const assignGroupForms = document.querySelectorAll('form.assign-group');

            assignGroupForms.forEach(form => {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault(); // Prevenir el envío del formulario estándar

                    const userId = form.dataset.userId; // Obtener el ID del usuario
                    const formData = new FormData(form);

                    try {
                        const response = await fetch('asignar_grupo.php', {
                            method: 'POST',
                            body: formData,
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Mostrar SweetAlert de éxito
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: result.message,
                                confirmButtonColor: '#3085d6',
                            }).then(() => {
                                // Eliminar el usuario de la lista
                                const userContainer = document.getElementById(`user-container-${userId}`);
                                if (userContainer) {
                                    userContainer.remove();
                                }
                            });
                        } else {
                            // Mostrar SweetAlert de error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message,
                                confirmButtonColor: '#d33',
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un problema al asignar el grupo.',
                            confirmButtonColor: '#d33',
                        });
                        console.error('Error:', error);
                    }
                });
            });

            // Manejo de eliminación de archivos
            const eliminarBotones = document.querySelectorAll('.eliminar-archivo');

            eliminarBotones.forEach(boton => {
                boton.addEventListener('click', async (e) => {
                    const userId = boton.dataset.userId;
                    const tipoArchivo = boton.dataset.tipoArchivo;

                    // Confirmar acción con SweetAlert
                    const confirm = await Swal.fire({
                        title: '¿Estás seguro?',
                        text: `Esto eliminará el ${tipoArchivo} del usuario.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                    });

                    if (!confirm.isConfirmed) return;

                    // Enviar solicitud de eliminación
                    try {
                        const formData = new FormData();
                        formData.append('user_id', userId);
                        formData.append('tipo_archivo', tipoArchivo);

                        const response = await fetch('eliminar_archivo.php', {
                            method: 'POST',
                            body: formData,
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: result.message,
                                confirmButtonColor: '#3085d6',
                            });

                            // Actualizar la interfaz
                            const archivoDiv = boton.closest('div');
                            if (archivoDiv) {
                                archivoDiv.remove();
                            }
                        } else {
                            // Mostrar mensaje de error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message,
                                confirmButtonColor: '#d33',
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un problema al eliminar el archivo.',
                            confirmButtonColor: '#d33',
                        });
                        console.error('Error:', error);
                    }
                });
            });
        });

        // Funcionalidad para alternar visibilidad de grupos
        function togglePanel(id) {
            const panel = document.getElementById(id);
            if (panel) {
                panel.classList.toggle("hidden");
            }
        }
    </script>
    <script src="../assets/js/upload_archivo.js"></script>
</body>
</html>