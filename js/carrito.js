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
            carrito.innerHTML += '<button onclick="window.location.href=\'./index.php\'">Seguir comprando</button>';
            return;
        }

        const itemsContainer = document.createElement('div');
        itemsContainer.className = 'carrito-items';

        carritoData.forEach((item, index) => {
            const itemElement = document.createElement('div');
            itemElement.className = 'carrito-item';
            itemElement.innerHTML = `
                <div style="display: flex; align-items: center;">
                    <img src="${item.imagen}" alt="${item.nombre}" style="width: 50px; height: 50px; margin-right: 10px; object-fit: cover;">
                    <div>
                        <div><strong>${item.nombre}</strong></div>
                        <div>${formatearPrecio(item.precio)}</div>
                        <div>Talla: ${item.talla} | Color: ${item.color}</div>
                    </div>
                    <div style="margin-left: auto; display: flex; align-items: center;">
                        <button onclick="modificarCantidad(${index}, -1)" style="background: none; border: none; padding: 0 5px; color: black;">-</button>
                        <span>${item.cantidad}</span>
                        <button onclick="modificarCantidad(${index}, 1)" style="background: none; border: none; padding: 0 5px; color: black;">+</button>
                        <button onclick="eliminarDelCarrito(${index})" style="background: none; border: none; color: red; margin-left: 10px;">🗑️</button>
                    </div>
                </div>
            `;
            itemsContainer.appendChild(itemElement);
        });

        carrito.appendChild(itemsContainer);

        // Agregar el total y botones
        const totalElement = document.createElement('div');
        totalElement.style.marginTop = '10px';
        totalElement.innerHTML = `
            <div style="font-weight: bold; margin-bottom: 10px;">
                Total: ${formatearPrecio(calcularTotal())}
            </div>
            <button onclick="window.location.href='./carrito.php'">Ir al Carrito</button>
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

        // Verificar si la nueva cantidad está en el rango permitido (1-10)
        if (nuevaCantidad > 0 && nuevaCantidad <= 10) {
            itemActual.cantidad = nuevaCantidad;
            actualizarCarrito();
        } else if (nuevaCantidad === 0) {
            eliminarDelCarrito(index);
        } else {
            // Mostrar mensaje visual si se excede el límite de cantidad
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

    // Event listener para el botón del carrito
    carritoBtn.addEventListener("click", (event) => {
        event.stopPropagation();
        carrito.classList.toggle("show");
        actualizarCarritoUI();
    });

    // Prevenir que los clics dentro del carrito cierren el carrito
    carrito.addEventListener("click", (event) => {
        event.stopPropagation();
    });

    // Cerrar el carrito cuando se hace clic fuera
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
