    function validarSesion() {
        fetch(BASE_PATH + 'validar-sesion')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (!data.sesionActiva) {
                    alert('Sesión expirada. Redirigiendo...');
                    window.location.href = BASE_PATH;
                }
            })
            .catch(error => {
                console.error('Error al validar sesión:', error);
            });
    }
    // Ejecutar inmediatamente al cargar
    validarSesion();
    // Repetir cada 10 segundos (10000 ms)
    setInterval(validarSesion, 10000);