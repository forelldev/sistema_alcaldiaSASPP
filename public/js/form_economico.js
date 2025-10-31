document.addEventListener('DOMContentLoaded', () => {
  const tipoAyuda = document.getElementById('categoria');
  const contenedor = document.getElementById('formulario-socioeconomico');

  if (tipoAyuda && contenedor) {
    tipoAyuda.addEventListener('change', () => {
      const valor = tipoAyuda.value.trim();
      contenedor.innerHTML = ''; // Limpiar contenido anterior

      if (valor === 'Ayuda Económica') {
        contenedor.innerHTML = `
          <div class="titulo-seccion"><i class="fa fa-file-alt"></i> Estudio Socioeconómico</div>
          <div class="fila-formulario">
            <div class="campo-formulario">
              <label for="ingresos_mensuales">Ingresos Mensuales:</label>
              <input type="text" id="ingresos_mensuales" name="ingresos_mensuales" required 
                oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Ejemplo: 500 Bs">
            </div>
            <div class="campo-formulario">
              <label for="gastos_mensuales">Gastos Mensuales:</label>
              <input type="text" id="gastos_mensuales" name="gastos_mensuales" required 
                oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Ejemplo: 300 Bs">
            </div>
          </div>
          <div class="fila-formulario">
            <div class="campo-formulario">
              <label for="personas_dependientes">Personas Dependientes:</label>
              <select id="personas_dependientes" name="personas_dependientes" required>
                <option value="">Seleccione</option>
                ${Array.from({ length: 10 }, (_, i) => `<option value="${i + 1}">${i + 1}</option>`).join('')}
              </select>
            </div>
            <div class="campo-formulario">
              <label for="situacion_laboral">Situación Laboral:</label>
              <select id="situacion_laboral" name="situacion_laboral" required>
                <option value="">Seleccione</option>
                <option value="Desempleado">Desempleado</option>
                <option value="Empleado">Empleado</option>
                <option value="Informal">Informal</option>
                <option value="Jubilado">Jubilado</option>
              </select>
            </div>
          </div>
          <div class="fila-formulario">
            <div class="campo-formulario">
              <label for="justificacion_ayuda">Justificación de la Ayuda:</label>
              <textarea id="justificacion_ayuda" name="justificacion_ayuda" rows="4" required 
                placeholder="Explique por qué necesita la ayuda económica..."></textarea>
            </div>
          </div>
        `;
      }
    });
  }
});
