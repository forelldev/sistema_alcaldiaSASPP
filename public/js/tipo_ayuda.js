document.addEventListener('DOMContentLoaded', function () {
    const categoriaSelect = document.getElementById('categoria');
    const tipoAyudaSelect = document.getElementById('tipo_ayuda');
    const tipoAyudaDiv = tipoAyudaSelect.closest('.campo-formulario');

    function toggleTipoAyuda() {
        if (categoriaSelect.value === 'Ayudas Tecnicas') {
            tipoAyudaDiv.style.display = 'block';
            tipoAyudaSelect.setAttribute('required', 'required');
        } else {
            tipoAyudaDiv.style.display = 'none';
            tipoAyudaSelect.removeAttribute('required');
            tipoAyudaSelect.value = ''; // limpiar selección
        }
    }

    // Ejecutar al cargar la página
    toggleTipoAyuda();

    // Ejecutar al cambiar la categoría
    categoriaSelect.addEventListener('change', toggleTipoAyuda);
});