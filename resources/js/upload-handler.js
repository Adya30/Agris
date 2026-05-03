window.previewImage = function(input, previewId = 'previewImg', placeholderId = 'placeholderIcon', containerId = 'imageContainer', errorId = 'clientError') {
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(placeholderId);
    const container = document.getElementById(containerId);
    const clientError = document.getElementById(errorId);
    const maxSize = 10 * 1024 * 1024; // 10MB

    if (clientError) {
        clientError.classList.add('hidden');
        clientError.innerText = "";
    }
    if (container) {
        container.classList.remove('border-red-500');
        container.classList.add('border-gray-300');
    }

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validasi Ukuran
        if (file.size > maxSize) {
            if (clientError) {
                clientError.innerText = "Ukuran foto terlalu besar (Maks. 10MB)";
                clientError.classList.remove('hidden');
            }
            if (container) {
                container.classList.replace('border-gray-300', 'border-red-500');
            }
            input.value = "";
            if (preview) preview.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
            return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        }
        reader.readAsDataURL(file);
    }
}
