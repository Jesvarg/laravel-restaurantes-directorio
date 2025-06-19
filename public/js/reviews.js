document.addEventListener('DOMContentLoaded', function () {
    const deleteReviewForms = document.querySelectorAll('.delete-review-form');

    deleteReviewForms.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); 

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, ¡eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); 
                }
            });
        });
    });
});