   //PARA TRABAJO CUADROS DE TRABAJOS

function mostrarCampoTrabajo() {
    const trabaja = document.getElementById('trabajo1').value;
    const campo = document.getElementById('campoTrabajo');
    const campoInstitucion = document.getElementById('campoInstitucion');

    const campos = [
        'trabajo',
        'direccion_trabajo',
        'trabaja_public'
    ];

    if (trabaja === 'Si') {
        campo.style.display = 'block';
        campos.forEach(id => {
            document.getElementById(id).setAttribute('required', 'required');
        });
    } else {
        campo.style.display = 'none';
        campos.forEach(id => {
            const input = document.getElementById(id);
            input.removeAttribute('required');
            input.value = '';
        });

        // Ocultar también el campo de institución
        campoInstitucion.style.display = 'none';
        const institucionInput = document.getElementById('nombre_insti');
        institucionInput.removeAttribute('required');
        institucionInput.value = '';
    }
}

function mostrarInstitucion() {
    const trabajaPublico = document.getElementById('trabaja_public').value;
    const campoInstitucion = document.getElementById('campoInstitucion');
    const institucionInput = document.getElementById('nombre_insti');

    if (trabajaPublico === 'Si') {
        campoInstitucion.style.display = 'block';
        institucionInput.setAttribute('required', 'required');
    } else {
        campoInstitucion.style.display = 'none';
        institucionInput.removeAttribute('required');
        institucionInput.value = '';
    }
}

window.addEventListener('DOMContentLoaded', () => {
    const trabajoInput = document.getElementById('trabajo');
    const trabajoSelect = document.getElementById('trabajo1');

    if (trabajoInput && trabajoSelect) {
        const valorTrabajo = trabajoInput.value.trim().toLowerCase();

        if (valorTrabajo && valorTrabajo !== 'no tiene') {
            trabajoSelect.value = 'Si';
            mostrarCampoTrabajo(); // activa campos relacionados
        } else {
            trabajoSelect.value = 'No';
            trabajoInput.value = 'No tiene';
            mostrarCampoTrabajo(); // desactiva campos relacionados
        }
    }

    // También inicializa el campo de institución si aplica
    const trabajaPublico = document.getElementById('trabaja_public');
    if (trabajaPublico && trabajaPublico.value === 'Si') {
        mostrarInstitucion();
    }
});
