document.addEventListener('DOMContentLoaded', function () {
  const ctx = document.getElementById('graficaSolicitudes').getContext('2d');
  const grafica = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: [
        'En Espera del Documento',
        'En proceso 1/3',
        'En proceso 2/3',
        'En proceso 3/3',
        'Solicitudes finalizadas'
      ],
      datasets: [{
        label: 'Cantidad de Solicitudes',
        data: window.solicitudesData,
        backgroundColor: [
          '#3b4cca', // En espera (igual al contador)
          '#ffd600', // Proceso (igual al contador)
          '#ffd600', // Proceso (igual al contador)
          '#ffd600', // Proceso (igual al contador)
          '#4caf50'  // Finalizadas (igual al contador)
        ]
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1
          }
        }
      }
    }
  });
});
