document.addEventListener('DOMContentLoaded', () => {
    const lista = document.getElementById('lista-notificaciones');
    const badge = document.getElementById('noti-total');
    const marcar = document.getElementById('marcar-vistas');
    const barra = document.getElementById('barra-notificaciones');
    const btn = document.getElementById('btn-notificaciones');

    // Mostrar/ocultar barra al hacer clic
    btn.addEventListener('click', () => {
        if (barra.style.display === 'none' || barra.style.display === '') {
            barra.style.display = 'block';
        } else {
            barra.style.display = 'none';
        }
    });

    // Cargar notificaciones al iniciar
    cargarNotificaciones();

    // Actualizar cada 60 segundos
    setInterval(cargarNotificaciones, 20000);

    function cargarNotificaciones() {
        fetch(BASE_PATH + 'casos_ajax')
            .then(res => res.json())
            .then(data => {
                // Limpiar lista
                lista.innerHTML = '';
                let total = 0;

                if (data.exito && data.datos?.casos?.datos instanceof Array) {
                    const casos = data.datos.casos.datos;
                    total = casos.length;

                    if (total > 0) {
                        casos.forEach(noti => {
                            const item = document.createElement('li');
                            item.className = 'notificacion-item';
                            item.innerHTML = `
                                <strong>Casos:</strong>
                                <a href="${BASE_URL}/noti_caso?id_caso=${encodeURIComponent(noti.id_caso)}">
                                    ${noti.descripcion}<br>
                                    ${noti.estado ?? 'Sin mensaje'}
                                    <span class="fecha">${formatearFecha(noti.fecha)}</span>
                                </a>
                            `;
                            lista.appendChild(item);
                        });
                        marcar.style.display = 'block';
                    } else {
                        lista.innerHTML = '<li class="notificacion-item">No hay nuevos casos por atender.</li>';
                        marcar.style.display = 'none';
                    }
                } else {
                    lista.innerHTML = `<li class="notificacion-item">${data.mensaje ?? 'Error al cargar notificaciones.'}</li>`;
                    marcar.style.display = 'none';
                }

                badge.textContent = total;
                badge.style.display = total > 0 ? 'inline-block' : 'none';
            })
            .catch(err => {
                lista.innerHTML = '<li class="notificacion-item">Error de conexión.</li>';
                console.error(err);
            });
    }

    function formatearFecha(fechaStr) {
        const fecha = new Date(fechaStr);
        return fecha.toLocaleDateString('es-VE') + ' ' + fecha.toLocaleTimeString('es-VE', { hour: '2-digit', minute: '2-digit' });
    }

    // Inicialmente ocultar el panel
    barra.style.display = 'none';
});

