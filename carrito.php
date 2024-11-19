<!DOCTYPE html>
<html lang="es-CO">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="descripcion" content="⚡TIENDA VIRTUAL⚡ ¡TODOS LOS DISEÑOS DISPONIBLES! ENVIOS A TODO COLOMBIA 🇨🇴">
    <meta name="keywords" content="ropa, tienda de ropa, moda, descuentos, ropa de hombre, ropa de mujer">
    <meta name="author" content="Tienda de Ropa Venneta">
    <title>Venneta - Carrito</title>
    <link rel="icon" type="image/jpg" href="./img/venneta_logo.png">
    <link rel="stylesheet" href="./css/carrito.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Jura:wght@300..700&display=swap">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <div id="carrito-contenido">
            <h3>Tu carrito</h3>
            <div id="productos-carrito"></div>
            <div id="total-carrito"></div>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const productosCarrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const contenedorProductos = document.getElementById('productos-carrito');
            const contenedorTotal = document.getElementById('total-carrito');

            const actualizarCarrito = () => {
                contenedorProductos.innerHTML = '';
                let total = 0;

                if (productosCarrito.length === 0) {
                    contenedorProductos.innerHTML = '<p>Tu carrito está vacío</p>';
                    contenedorProductos.innerHTML += '<button onclick="window.location.href=\'./index.php\'">Seguir comprando</button>';
                    return;
                }

                productosCarrito.forEach((producto, index) => {
                    const productoElement = document.createElement('div');
                    productoElement.className = 'carrito-item';
                    productoElement.innerHTML = `
                        <div style="display: flex; align-items: center;">
                            <img src="${producto.imagen}" alt="${producto.nombre}" style="width: 50px; height: 50px; margin-right: 10px; object-fit: cover;">
                            <div>
                                <div><strong>${producto.nombre}</strong></div>
                                <div>${formatearPrecio(producto.precio)}</div>
                                <div>Talla: ${producto.talla} | Color: ${producto.color}</div>
                            </div>
                            <div style="margin-left: auto; display: flex; align-items: center;">
                                <span>${producto.cantidad}</span>
                            </div>
                        </div>
                    `;
                    contenedorProductos.appendChild(productoElement);
                    total += producto.precio * producto.cantidad;
                });

                contenedorTotal.innerHTML = `
                    <div style="font-weight: bold; margin: 0 0 1rem 0;">
                        Total: ${formatearPrecio(total)}
                    </div>
                    <button onclick="window.location.href='./checkout.php'">Proceder al Pago</button>
                `;
            };

            actualizarCarrito();

            window.addEventListener('storage', (event) => {
                if (event.key === 'carrito') {
                    actualizarCarrito();
                }
            });
        });

        const formatearPrecio = (precio) => {
            return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 0
            }).format(precio);
        };
    </script>
    <?php include 'includes/footer.php'; ?>
</body>

</html>