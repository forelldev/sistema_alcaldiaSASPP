
function mostrarMensaje(mensaje, tipo = 'info', duracion = 3000) {
    // Elimina mensaje anterior si existe
    const anterior = document.querySelector('.mensaje-flotante');
    if (anterior) anterior.remove();

    // Crea el contenedor del mensaje
    const div = document.createElement('div');
    div.className = `mensaje-flotante mensaje-${tipo}`;
    div.textContent = mensaje;

    // Estilos flotante mejorados para login y sobreponer arriba de todo
    div.style.position = 'fixed';
    div.style.top = '0';
    div.style.left = '50%';
    div.style.transform = 'translateX(-50%)';
    div.style.zIndex = '2147483647'; // Máximo z-index posible
    div.style.padding = '18px 32px';
    div.style.borderRadius = '0 0 12px 12px';
    div.style.background = tipo === 'error' ? '#ff4d4f' : '#007bff';
    div.style.color = '#fff';
    div.style.boxShadow = '0 4px 16px rgba(0,0,0,0.22)';
    div.style.fontSize = '1.15rem';
    div.style.maxWidth = '95vw';
    div.style.width = 'fit-content';
    div.style.boxSizing = 'border-box';
    div.style.textAlign = 'center';
    div.style.pointerEvents = 'none'; // Evita que bloquee clics

    document.body.appendChild(div);

    setTimeout(() => {
        div.remove();
    }, duracion);
}