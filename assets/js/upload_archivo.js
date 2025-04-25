document.addEventListener('DOMContentLoaded', () => {
    const uploadForms = document.querySelectorAll('.upload-form');

    uploadForms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault(); // Prevenir el envío estándar del formulario

            const formData = new FormData(form);
            const userId = form.querySelector('input[name="user_id"]').value;

            try {
                const response = await fetch(form.action, {
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
                    });

                    // Actualizar la interfaz dinámicamente
                    if (formData.has('manual')) {
                        const manualContainer = document.querySelector(`#group-user-${userId} .manual-container`);
                        if (manualContainer) {
                            const newManualPath = `../assets/pdfs/manuales/${formData.get('manual').name}`;
                            manualContainer.innerHTML = `
                                <div class="flex items-center gap-2">
                                    <span class="text-green-500">Manual cargado: <a href="${newManualPath}" target="_blank" class="underline">Ver manual</a></span>
                                    <button class="text-red-500 hover:underline eliminar-archivo" data-user-id="${userId}" data-tipo-archivo="manual_path">Eliminar</button>
                                </div>
                            `;
                        }
                    }

                    if (formData.has('diploma')) {
                        const diplomaContainer = document.querySelector(`#group-user-${userId} .diploma-container`);
                        if (diplomaContainer) {
                            const newDiplomaPath = `../assets/pdfs/diplomas/${formData.get('diploma').name}`;
                            diplomaContainer.innerHTML = `
                                <div class="flex items-center gap-2">
                                    <span class="text-green-500">Diploma cargado: <a href="${newDiplomaPath}" target="_blank" class="underline">Ver diploma</a></span>
                                    <button class="text-red-500 hover:underline eliminar-archivo" data-user-id="${userId}" data-tipo-archivo="diploma_path">Eliminar</button>
                                </div>
                            `;
                        }
                    }
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
                    title: 'Error inesperado',
                    text: 'Ocurrió un problema al procesar tu solicitud.',
                    confirmButtonColor: '#d33',
                });
                console.error('Error:', error);
            }
        });
    });
});