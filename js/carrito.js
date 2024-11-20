document.addEventListener("DOMContentLoaded", () => {
    const carritoBtn = document.querySelector("#carrito-btn");
    const carrito = document.querySelector("#carrito");
    const contadorCarrito = document.getElementById('contador-carrito');
    const isCarritoPage = window.location.pathname.includes('carrito.php');

    // Safe localStorage initialization
    const initializeCarrito = () => {
        try {
            let carritoData = JSON.parse(localStorage.getItem('carrito'));
            return Array.isArray(carritoData) ? carritoData : [];
        } catch (error) {
            console.error("Error initializing cart:", error);
            return [];
        }
    };

    let carritoData = initializeCarrito();

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
        if (!carrito) return;

        // No mostrar el popup si estamos en la página del carrito
        if (isCarritoPage) {
            carrito.style.display = 'none';
            return;
        }

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

    // Event listeners para el botón del carrito
    if (carritoBtn && carrito) {
        carritoBtn.addEventListener("click", (event) => {
            // Si estamos en la página del carrito, no hacer nada
            if (isCarritoPage) {
                event.preventDefault();
                return;
            }

            event.stopPropagation();
            carrito.classList.toggle("show");
            actualizarCarritoUI();
        });

        // Solo agregar el listener de documento si no estamos en la página del carrito
        if (!isCarritoPage) {
            document.addEventListener("click", (event) => {
                const isClickInsideCarrito = carrito.contains(event.target);
                const isClickOnCarritoBtn = carritoBtn.contains(event.target);

                if (!isClickInsideCarrito && !isClickOnCarritoBtn) {
                    carrito.classList.remove("show");
                }
            });
        }
    }

    // Función para actualizar el contador del carrito
    const actualizarContadorCarrito = () => {
        if (contadorCarrito) {
            const totalItems = carritoData.reduce((total, item) => total + item.cantidad, 0);
            if (totalItems > 0) {
                contadorCarrito.textContent = totalItems;
                contadorCarrito.classList.remove('hidden');
                contadorCarrito.classList.add('pop-animation');
            } else {
                contadorCarrito.classList.add('hidden');
                contadorCarrito.classList.remove('pop-animation');
            }
        }
    };

    // Funciones para modificar cantidades y eliminar items
    window.modificarCantidad = (index, cambio) => {
        if (index >= 0 && index < carritoData.length) {
            const nuevaCantidad = carritoData[index].cantidad + cambio;
            if (nuevaCantidad > 0) {
                carritoData[index].cantidad = nuevaCantidad;
            } else {
                carritoData.splice(index, 1);
            }
            localStorage.setItem('carrito', JSON.stringify(carritoData));
            actualizarCarritoUI();
            actualizarContadorCarrito();
        }
    };

    window.eliminarDelCarrito = (index) => {
        if (index >= 0 && index < carritoData.length) {
            carritoData.splice(index, 1);
            localStorage.setItem('carrito', JSON.stringify(carritoData));
            actualizarCarritoUI();
            actualizarContadorCarrito();
        }
    };

    // Inicializar el carrito
    actualizarContadorCarrito();
    if (!isCarritoPage) {
        actualizarCarritoUI();
    }

    // Escuchar cambios en localStorage
    window.addEventListener('storage', (event) => {
        if (event.key === 'carrito') {
            carritoData = JSON.parse(event.newValue) || [];
            actualizarContadorCarrito();
            if (!isCarritoPage) {
                actualizarCarritoUI();
            }
        }
    });
});