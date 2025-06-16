document.addEventListener('DOMContentLoaded', function () {
    // Confirmación para eliminar restaurantes
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!confirm('¿Estás seguro de que quieres eliminar este restaurante? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });
});