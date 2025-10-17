document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('btn-notificaciones');
  const barra = document.getElementById('barra-notificaciones');

  // Mover la barra al <body> para evitar clipping por padres
  if (barra && barra.parentElement !== document.body) {
    document.body.appendChild(barra);
  }

  btn.addEventListener('click', function (e) {
    e.stopPropagation();
    barra.classList.toggle('oculto');
  });

  // Cerrar al hacer clic fuera
  document.addEventListener('click', function (e) {
    if (!barra.contains(e.target) && !btn.contains(e.target)) {
      barra.classList.add('oculto');
    }
  });
});
