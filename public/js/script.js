document.addEventListener("DOMContentLoaded", () => {
    const pedidoForm = document.getElementById("pedidoForm");

    pedidoForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const nombre = document.getElementById("materialNombre").value.trim();
        const cantidad = parseInt(document.getElementById("cantidadPedido").value);

        fetch("../../app/controllers/PedidoController.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `nombre=${encodeURIComponent(nombre)}&cantidad=${cantidad}`,
        })
            .then((r) => r.json())
            .then((json) => {
                if (json.success) {
                    alert("¡Pedido registrado exitosamente!");
                    pedidoForm.reset();
                    bootstrap.Modal.getInstance(
                        document.getElementById("pedidoModal")
                    ).hide();
                } else alert("Error: " + json.error);
            })
            .catch(() => alert("Error al conectar con el servidor."));
    });

    const inputBuscar = document.getElementById("buscarProducto");
    inputBuscar.addEventListener("keyup", () => {
        const filtro = inputBuscar.value.toLowerCase();

        document
            .querySelectorAll("#inventarioTablaBody tr")
            .forEach((fila) => {
                const nombre = fila.children[1].textContent.toLowerCase();
                const descripcion = fila.children[2].textContent.toLowerCase();

                if (nombre.includes(filtro) || descripcion.includes(filtro)) {
                    fila.style.display = "";
                } else {
                    fila.style.display = "none";
                }
            });
    });

});
