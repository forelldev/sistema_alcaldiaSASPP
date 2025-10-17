document.addEventListener("DOMContentLoaded", function () {
  const categoriaSelect = document.getElementById("categoria");
  const tipoAyudaSelect = document.getElementById("tipo_ayuda");
  const tipoAyudaContainer = document.getElementById("tipoAyudaContainer");

  const opcionesPorCategoria = {
    "Salud": ["Medicamentos", "Ayudas Técnicas", "Operaciones", "Exámenes", "Estudios", "Consultas"],
    "Ayuda Económica": ["Enseres"],
    "Materiales de Construcción": ["Sacos de cemento", "Bloques", "Tuberías", "Tubos", "Cables"],
    "Varios": ["Tanques de Agua"]
  };

  // Ocultar al cargar
  tipoAyudaContainer.style.display = "none";

  categoriaSelect.addEventListener("change", function () {
    const categoria = categoriaSelect.value;
    const opciones = opcionesPorCategoria[categoria] || [];

    if (categoria === "") {
      tipoAyudaContainer.style.display = "none";
      tipoAyudaSelect.innerHTML = "";
      return;
    }

    // Mostrar el contenedor
    tipoAyudaContainer.style.display = "block";

    // Limpiar y agregar opciones
    tipoAyudaSelect.innerHTML = "";
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Seleccione";
    tipoAyudaSelect.appendChild(defaultOption);

    opciones.forEach(opcion => {
      const optionElement = document.createElement("option");
      optionElement.value = opcion;
      optionElement.textContent = opcion;
      tipoAyudaSelect.appendChild(optionElement);
    });
  });

  tipoAyudaSelect.addEventListener("change", function () {
    if (tipoAyudaSelect.value === "Tuberías") {
      alert("Especificar en descripción si son tuberías blancas o negras");
    }
  });

  // PRIORIDAD:
const prioridadContainer = document.getElementById("prioridadContainer");
const prioridadSelect = document.getElementById("prioridad");

// Ocultar prioridad al cargar
prioridadContainer.style.display = "none";
prioridadSelect.removeAttribute("required");

categoriaSelect.addEventListener("change", function () {
  const categoria = categoriaSelect.value;

  if (categoria === "Salud") {
    prioridadContainer.style.display = "block";
    prioridadSelect.setAttribute("required", "required");
  } else {
    prioridadContainer.style.display = "none";
    prioridadSelect.removeAttribute("required");
    prioridadSelect.value = ""; // Limpiar selección si se oculta
  }
});
});



