document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        const btn = dropdown.querySelector('.dropdown-toggle');
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            // Cierra otros menús abiertos
            dropdowns.forEach(d => { if (d !== dropdown) d.classList.remove('open'); });
            dropdown.classList.toggle('open');
        });
    });
    document.addEventListener('click', function() {
        dropdowns.forEach(d => d.classList.remove('open'));
    });
});