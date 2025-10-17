        // Este script fuerza la recarga de la página si se accede desde la caché del historial.
        window.onpageshow = function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        };