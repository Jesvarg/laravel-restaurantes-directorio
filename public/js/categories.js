document.addEventListener('DOMContentLoaded', function () {
    const deleteForms = document.querySelectorAll('form.delete-form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            // Intentar obtener el nombre de la categoría de un elemento específico si está disponible
            let categoryName = 'esta categoría'; // Nombre por defecto
            const cardTitleElement = form.closest('.category-card')?.querySelector('.card-title');
            if (cardTitleElement) {
                categoryName = `"${cardTitleElement.textContent.trim()}"`;
            }
            
            Swal.fire({
                title: '¿Estás seguro?',
                html: `Vas a eliminar ${categoryName}.<br><small class='text-muted'>Esta acción desvinculará la categoría de los restaurantes, pero no eliminará los restaurantes en sí.</small>`, 
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--bs-danger)', // Usar variable de Bootstrap para consistencia
                cancelButtonColor: 'var(--bs-secondary)', // Usar variable de Bootstrap para consistencia
                confirmButtonText: '<i class="bi bi-trash-fill"></i> Sí, ¡eliminar!',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'btn btn-danger me-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false // Deshabilitar estilos por defecto de SweetAlert para usar los de Bootstrap
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});