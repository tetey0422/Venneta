document.addEventListener("DOMContentLoaded", () => {
    const carritoBtn = document.querySelector("#carrito-btn");
    const carrito = document.querySelector("#carrito");

    // Cargar el carrito desde localStorage al iniciar
    let carritoData = JSON.parse(localStorage.getItem('carrito')) || [];

    // Función para formatear precio en pesos colombianos
    const formatearPrecio = (precio) => {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0
        }).format(precio);
    };

    // Función para calcular el total del carrito
    const calcularTotal = () => {
        return carritoData.reduce((total, item) => total + (item.precio * item.cantidad), 0);
    };

    // Función para actualizar la visualización del carrito
    const actualizarCarritoUI = () => {
        carrito.innerHTML = '<h3>Tu carrito</h3>';

        if (carritoData.length === 0) {
            carrito.innerHTML += '<p>Tu carrito está vacío</p>';
            carrito.innerHTML += '<button onclick="window.location.href=\'./diseno.php\'" class="btn-principal">Seguir comprando</button>';
            return;
        }

        const itemsContainer = document.createElement('div');
        itemsContainer.className = 'carrito-items';

        carritoData.forEach((item, index) => {
            const itemElement = document.createElement('div');
            itemElement.className = 'carrito-item';
            itemElement.innerHTML = `
                <div class="carrito-item-contenido">
                    <div class="carrito-item-imagen">
                        <img src="${item.imagen}" alt="${item.nombre}">
                    </div>
                    <div class="carrito-item-detalles">
                        <div class="carrito-item-nombre"><strong>${item.nombre}</strong></div>
                        <div class="carrito-item-precio">${formatearPrecio(item.precio)}</div>
                        <div class="carrito-item-specs">Talla: ${item.talla} | Color: ${item.color}</div>
                    </div>
                    <div class="carrito-item-controles">
                        <div class="cantidad-controles">
                            <button onclick="modificarCantidad(${index}, -1)" class="cantidad-btn">-</button>
                            <span class="cantidad-valor">${item.cantidad}</span>
                            <button onclick="modificarCantidad(${index}, 1)" class="cantidad-btn">+</button>
                        </div>
                        <button onclick="eliminarDelCarrito(${index})" class="eliminar-btn">🗑️</button>
                    </div>
                </div>
            `;
            itemsContainer.appendChild(itemElement);
        });

        carrito.appendChild(itemsContainer);

        // Agregar el total y botones
        const totalElement = document.createElement('div');
        totalElement.className = 'carrito-footer';
        totalElement.innerHTML = `
            <div class="carrito-total">
                Total: ${formatearPrecio(calcularTotal())}
            </div>
            <button onclick="window.location.href='./carrito.php'" class="carrito-checkout">Ir al Carrito</button>
        `;
        carrito.appendChild(totalElement);
    };

    // Función para proceder al pago en WhatsApp
    window.procederAlPago = () => {
        if (carritoData.length === 0) return;

        const mensaje = carritoData.map(item =>
            `- ${item.nombre} (${item.cantidad} x ${formatearPrecio(item.precio)}), Talla: ${item.talla}, Color: ${item.color}`
        ).join('%0A');
        const total = formatearPrecio(calcularTotal());

        const url = `https://api.whatsapp.com/send/?phone=%2B573046165621&text=Hola%2C+quiero+proceder+al+pago+de+mi+carrito%3A%0A${mensaje}%0A%0ATotal%3A+${total}`;
        window.open(url, '_blank');
    };

    // Función para modificar la cantidad de un producto
    window.modificarCantidad = (index, cambio) => {
        const itemActual = carritoData[index];
        const nuevaCantidad = itemActual.cantidad + cambio;

        if (nuevaCantidad > 0 && nuevaCantidad <= 10) {
            itemActual.cantidad = nuevaCantidad;
            actualizarCarrito();
        } else if (nuevaCantidad === 0) {
            eliminarDelCarrito(index);
        } else {
            alert('La cantidad no puede superar 10 unidades.');
        }
    };

    // Función para eliminar un item del carrito
    window.eliminarDelCarrito = (index) => {
        carritoData.splice(index, 1);
        actualizarCarrito();
    };

    // Actualiza el carrito en localStorage y la UI
    const actualizarCarrito = () => {
        localStorage.setItem('carrito', JSON.stringify(carritoData));
        actualizarCarritoUI();
        actualizarContadorCarrito();
    };

    // Event listeners
    carritoBtn.addEventListener("click", (event) => {
        event.stopPropagation();
        carrito.classList.toggle("show");
        actualizarCarritoUI();
    });

    carrito.addEventListener("click", (event) => {
        event.stopPropagation();
    });

    document.addEventListener("click", (event) => {
        if (!carrito.contains(event.target) && !carritoBtn.contains(event.target)) {
            carrito.classList.remove("show");
        }
    });

    // Actualizar contador de carrito
    const actualizarContadorCarrito = () => {
        const contadorCarrito = document.getElementById('contador-carrito');
        if (contadorCarrito) {
            contadorCarrito.textContent = carritoData.length;
        }
    };

    // Inicializar el carrito
    actualizarCarritoUI();
    actualizarContadorCarrito();
});