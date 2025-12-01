document.addEventListener("DOMContentLoaded", () => {

    const checkboxes = document.querySelectorAll('.asiento input[type="checkbox"]');
    const cantidadSpan = document.getElementById("cantidadAsientos");
    const totalSpan = document.getElementById("totalPagar");
    const precioUnitario = parseFloat(document.getElementById("precioUnitario").textContent);

    // Inputs ocultos que irán al PHP
    const inputCantidad = document.getElementById("cantidad_asientos");
    const inputTotal = document.getElementById("total_pagar");
    const inputPrecio = document.getElementById("precio_unitario_input");

    // Guardamos el precio unitario una sola vez
    inputPrecio.value = precioUnitario;

    function actualizarResumen() {
        let seleccionados = document.querySelectorAll('.asiento input[type="checkbox"]:checked').length;

        let total = (seleccionados * precioUnitario).toFixed(2);

        // Mostrar en pantalla
        cantidadSpan.textContent = seleccionados;
        totalSpan.textContent = total;

        // Enviar a PHP
        inputCantidad.value = seleccionados;
        inputTotal.value = total;
    }

    checkboxes.forEach(chk => {
        chk.addEventListener("change", actualizarResumen);
    });
});