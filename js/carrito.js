document.addEventListener("DOMContentLoaded", () => {
    const carritoBtn = document.querySelector("#carrito-btn");
    const carrito = document.querySelector("#carrito");
    
    // Función para formatear precio en pesos colombianos
    const formatearPrecio = (precio) => {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0
        }).format(precio);
    };

    // Función para calcular el total del carrito
    const calcularTotal = (items) => {
        return items.reduce((total, item) => total + (item.precio * item.cantidad), 0);
    };

    // Función para actualizar la visualización del carrito
    const actualizarCarritoUI = () => {
        const carritoItems = JSON.parse(localStorage.getItem('carrito')) || [];
        
        // Limpiar contenido actual
        carrito.innerHTML = '<h3>Tu carrito</h3>';
        
        if (carritoItems.length === 0) {
            carrito.innerHTML += '<p>Tu carrito está vacío</p>';
            carrito.innerHTML += '<button onclick="window.location.href=\'./index.php\'">Seguir comprando</button>';
            return;
        }

        // Crear contenedor para los items
        const itemsContainer = document.createElement('div');
        itemsContainer.className = 'carrito-items';

        // Agregar cada item al carrito
        carritoItems.forEach((item, index) => {
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

        // Agregar el total y botón de ir al carrito
        const totalElement = document.createElement('div');
        totalElement.style.marginTop = '10px';
        totalElement.innerHTML = `
            <div style="font-weight: bold; margin-bottom: 10px;">
                Total: ${formatearPrecio(calcularTotal(carritoItems))}
            </div>
            <button onclick="window.location.href='./carrito.php'">Ir al Carrito</button>
        `;
        carrito.appendChild(totalElement);
    };

    // Función para modificar la cantidad de un producto
    window.modificarCantidad = (index, cambio) => {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const itemActual = carrito[index];
        
        // Validar límites de cantidad (1-10)
        const nuevaCantidad = itemActual.cantidad + cambio;
        
        if (nuevaCantidad > 0 && nuevaCantidad <= 10) {
            itemActual.cantidad = nuevaCantidad;
            localStorage.setItem('carrito', JSON.stringify(carrito));
            actualizarCarritoUI();
            actualizarContadorCarrito();
        } else if (nuevaCantidad === 0) {
            // Si la cantidad llega a 0, eliminar el producto
            eliminarDelCarrito(index);
        }
    };

    // Función para eliminar un item del carrito
    window.eliminarDelCarrito = (index) => {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        carrito.splice(index, 1);
        localStorage.setItem('carrito', JSON.stringify(carrito));
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
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const contadorCarrito = document.getElementById('contador-carrito');
        if (contadorCarrito) {
            // Contar productos únicos, no la suma total de cantidades
            contadorCarrito.textContent = carrito.length;
        }
    };

    // Inicializar el carrito
    actualizarCarritoUI();
    actualizarContadorCarrito();
});