  //PARA CALCULAR EDAD
document.getElementById('fecha_nacimiento').addEventListener('change', function () {
    const fechaNacimiento = new Date(this.value);
    const hoy = new Date();

    let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
    const mes = hoy.getMonth() - fechaNacimiento.getMonth();
    const dia = hoy.getDate() - fechaNacimiento.getDate();

    // Ajustar si aún no ha cumplido años este año
    if (mes < 0 || (mes === 0 && dia < 0)) {
        edad--;
    }

    // Asignar la edad al campo oculto
    document.getElementById('edad').value = edad;
});