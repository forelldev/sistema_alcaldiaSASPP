document.addEventListener('DOMContentLoaded', function () {
  window.generarPDF = function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const headers = [];
    const rows = [];

    document.querySelectorAll('#exportarPDF thead th').forEach(th => {
      headers.push(th.innerText.trim());
    });

    document.querySelectorAll('#exportarPDF tbody tr').forEach(tr => {
      const row = [];
      tr.querySelectorAll('td').forEach(td => {
        row.push(td.innerText.trim());
      });
      rows.push(row);
    });

    doc.autoTable({
      head: [headers],
      body: rows,
      margin: { top: 20 },
      styles: {
        fontSize: 8,
        cellPadding: 3,
        overflow: 'linebreak',
        valign: 'middle'
      },
      headStyles: {
        fillColor: [52, 73, 94],
        textColor: 255,
        halign: 'center'
      },
      bodyStyles: {
        halign: 'left'
      },
      didDrawPage: function (data) {
        doc.setFontSize(12);
        doc.text("Reporte de Auditoría", data.settings.margin.left, 10);
      }
    });

    doc.save("reportes.pdf");
  };
});
