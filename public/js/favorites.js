document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const restaurantId = this.dataset.id;
            let isFavorited = this.dataset.favorited === 'true';
            const url = isFavorited ? `/restaurants/${restaurantId}/remove-favorite` : `/restaurants/${restaurantId}/add-favorite`;
            const method = 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json' // Asegurarse de que el servidor espera JSON
                },
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    this.dataset.favorited = !isFavorited;
                    const icon = this.querySelector('i');
                    icon.classList.toggle('bi-heart');
                    icon.classList.toggle('bi-heart-fill');
                    // Cambiar el color del botón y el texto si es necesario
                    if (!isFavorited) { // Si se acaba de marcar como favorito
                        this.classList.remove('btn-outline-danger');
                        this.classList.add('btn-danger');
                        this.title = 'Quitar de favoritos';
                    } else { // Si se acaba de quitar de favoritos
                        this.classList.remove('btn-danger');
                        this.classList.add('btn-outline-danger');
                        this.title = 'Añadir a favoritos';
                    }
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message || 'Ha ocurrido un error.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMessage = 'No se pudo completar la acción.';
                if (error && error.message) {
                    errorMessage = error.message;
                }
                toastr.error(errorMessage);
            });
        });
    });
});