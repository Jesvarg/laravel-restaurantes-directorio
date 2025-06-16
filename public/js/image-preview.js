document.addEventListener('DOMContentLoaded', function () {
    const photosInput = document.getElementById('photos');
    const imagePreviewContainer = document.getElementById('image-preview-container');

    if (photosInput && imagePreviewContainer) {
        photosInput.addEventListener('change', function (event) {
            // Limpiar previsualizaciones anteriores
            imagePreviewContainer.innerHTML = '';

            const files = event.target.files;
            if (files.length > 0) {
                const previewTitle = document.createElement('p');
                previewTitle.className = 'col-12 fw-semibold mb-2';
                previewTitle.textContent = 'Previsualización de nuevas fotos:';
                imagePreviewContainer.appendChild(previewTitle);
            }

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const col = document.createElement('div');
                        col.className = 'col-6 col-sm-4 col-md-3 mb-2'; // Ajusta las columnas según sea necesario

                        const card = document.createElement('div');
                        card.className = 'card h-100';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'card-img-top';
                        img.style.height = '120px';
                        img.style.objectFit = 'cover';
                        img.alt = 'Previsualización';

                        card.appendChild(img);
                        col.appendChild(card);
                        imagePreviewContainer.appendChild(col);
                    }
                    reader.readAsDataURL(file);
                }
            }
        });
    }
});