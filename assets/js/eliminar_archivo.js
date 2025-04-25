document.addEventListener('DOMContentLoaded', () => {
    const eliminarBotones = document.querySelectorAll('.eliminar-archivo');

    eliminarBotones.forEach(boton => {
        boton.addEventListener('click', async (e) => {
            const userId = boton.dataset.userId;
            const tipoArchivo = boton.dataset.tipoArchivo;

            // Validar que los datos existen antes de enviarlos
            if (!userId || !tipoArchivo) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Datos incompletos para la eliminación.',
                    confirmButtonColor: '#d33',
                });
                return;
            }

            // Confirmar acción con SweetAlert
            const confirm = await Swal.fire({
                title: '¿Estás seguro?',
                text: `Esto eliminará el ${tipoArchivo === 'manual_path' ? 'manual' : 'diploma'} del usuario.`,
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