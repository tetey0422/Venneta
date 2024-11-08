document.addEventListener("DOMContentLoaded", () => {
    const carritoBtn = document.querySelector("#carrito-btn"); // Botón para abrir el carrito
    const carrito = document.querySelector("#carrito");

    carritoBtn.addEventListener("click", (event) => {
        event.stopPropagation(); // Evitar que el clic en el botón cierre el carrito
        carrito.classList.toggle("show");
    });

    // Cerrar el carrito cuando se haga clic en cualquier otro lugar de la página
    document.addEventListener("click", (event) => {
        if (!carrito.contains(event.target) && !carritoBtn.contains(event.target)) {
            carrito.classList.remove("show");
        }
    });
});