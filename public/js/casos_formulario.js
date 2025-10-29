
document.addEventListener('DOMContentLoaded', () => {
  const direccionSelect = document.getElementById('direccion');
  const contenedorCampos = document.getElementById('campos-dinamicos');

  // Datos dinámicos actualizados
  const datos = {
    'Desarrollo Social': {
      'Medicamentos': ['Antibióticos', 'Analgésicos', 'Antiinflamatorios'],
      'Laboratorio': ['Exámenes de sangre', 'Orina', 'Heces'],
      'Ayudas Técnicas': [
        'Silla de Ruedas',
        'Silla de Ruedas(Niño)',
        'Andadera',
        'Andadera (Niño)',
        'Bastón 1 Punta',
        'Bastón 3 Puntas',
        'Bastón 4 Puntas',
        'Muletas',
        'Muletas (Niño)',
        'Collarín',
        'Colchón Anti-escaras'
      ],
      'Enseres': ['Muebles'],
      'Económica': ['Exámen']
    },
    'Despacho': {
      'Utiles': ['Cuadernos', 'Lápices', 'Mochilas'],
      'Grado': ['Primaria', 'Secundaria', 'Universidad']
    },
    'Vivienda': {
      'Reparaciones': ['Techo', 'Baño', 'Paredes'],
      'Materiales': ['Cemento', 'Bloques', 'Pintura']
    }
  };

  // Rellenar select de oficinas
  Object.keys(datos).forEach(oficina => {
    const option = document.createElement('option');
    option.value = oficina;
    option.textContent = oficina;
    direccionSelect.appendChild(option);
  });

  // Evento: al seleccionar oficina
  direccionSelect.addEventListener('change', () => {
    contenedorCampos.innerHTML = ''; // limpiar campos anteriores

    const oficina = direccionSelect.value;
    if (!oficina || !datos[oficina]) return;

    // Crear campo de categoría
    const labelCat = document.createElement('label');
    labelCat.setAttribute('for', 'categoria');
    labelCat.textContent = 'Categoría:';

    const selectCat = document.createElement('select');
    selectCat.name = 'categoria';
    selectCat.id = 'categoria';
    selectCat.required = true;
    selectCat.innerHTML = '<option value="">Seleccione</option>';

    Object.keys(datos[oficina]).forEach(categoria => {
      const option = document.createElement('option');
      option.value = categoria;
      option.textContent = categoria;
      selectCat.appendChild(option);
    });

    contenedorCampos.appendChild(labelCat);
    contenedorCampos.appendChild(selectCat);

    // Evento: al seleccionar categoría
    selectCat.addEventListener('change', () => {
      // Eliminar tipo_ayuda si ya existe
      const tipoExistente = document.getElementById('tipo_ayuda');
      if (tipoExistente) {
        tipoExistente.previousElementSibling.remove(); // eliminar label
        tipoExistente.remove(); // eliminar select
      }

      const categoria = selectCat.value;
      if (!categoria || !datos[oficina][categoria]) return;

      // Crear campo de tipo de ayuda
      const labelTipo = document.createElement('label');
      labelTipo.setAttribute('for', 'tipo_ayuda');
      labelTipo.textContent = 'Tipo de ayuda:';

      const selectTipo = document.createElement('select');
      selectTipo.name = 'tipo_ayuda';
      selectTipo.id = 'tipo_ayuda';
      selectTipo.required = true;
      selectTipo.innerHTML = '<option value="">Seleccione</option>';

      datos[oficina][categoria].forEach(tipo => {
        const option = document.createElement('option');
        option.value = tipo;
        option.textContent = tipo;
        selectTipo.appendChild(option);
      });

      contenedorCampos.appendChild(labelTipo);
      contenedorCampos.appendChild(selectTipo);
    });
  });
});

