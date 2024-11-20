<!DOCTYPE html>
<html lang="es-CO">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="descripcion" content="⚡TIENDA VIRTUAL⚡ ¡TODOS LOS DISEÑOS DISPONIBLES! ENVIOS A TODO COLOMBIA 🇨🇴">
    <meta name="keywords" content="ropa, tienda de ropa, moda, descuentos, ropa de hombre, ropa de mujer">
    <meta name="author" content="Tienda de Ropa Venneta">
    <title>Venneta</title>
    <link rel="icon" type="image/jpg" href="./img/venneta_logo.png">
    <link rel="stylesheet" href="./css/anadir.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Jura:wght@300..700&display=swap">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <?php
    include 'includes/config.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $nombre = isset($_GET['nombre']) ? urldecode($_GET['nombre']) : '';
    $imagen = isset($_GET['imagen']) ? urldecode($_GET['imagen']) : '';
    $precio = isset($_GET['precio']) ? urldecode($_GET['precio']) : '';

    // Buscar descripción del producto en la base de datos
    $stmt_producto = $conn->prepare("SELECT cDescripcion FROM TProducto WHERE cNombre = ?");
    $stmt_producto->bind_param("s", $nombre);
    $stmt_producto->execute();
    $result_producto = $stmt_producto->get_result();
    $descripcion = ($row = $result_producto->fetch_assoc()) ? $row['cDescripcion'] : 'Descripción no disponible';

    // Verificar conexión y parámetros
    if (empty($nombre) || empty($imagen) || empty($precio)) {
        echo "Información del producto incompleta.";
        exit;
    }

    // Consulta de tallas disponibles
    $stmt_tallas = $conn->prepare("SELECT DISTINCT t.cTalla 
        FROM TTalla_Color_Producto tcp
        JOIN TTalla t ON tcp.nTallaID = t.nTallaID 
        JOIN TProducto p ON tcp.nProductoID = p.nProductoID 
        WHERE p.cNombre = ? AND tcp.nCantidad > 0");
    $stmt_tallas->bind_param("s", $nombre);
    $stmt_tallas->execute();
    $result_tallas = $stmt_tallas->get_result();

    // Consulta de colores disponibles
    $stmt_colores = $conn->prepare("SELECT DISTINCT c.cColor 
        FROM TTalla_Color_Producto tcp
        JOIN TColor c ON tcp.nColorID = c.nColorID 
        JOIN TProducto p ON tcp.nProductoID = p.nProductoID 
        WHERE p.cNombre = ? AND tcp.nCantidad > 0");
    $stmt_colores->bind_param("s", $nombre);
    $stmt_colores->execute();
    $result_colores = $stmt_colores->get_result();
    ?>

    <main>
        <div class="producto">
            <div class="img-des">
                <img src="<?php echo $imagen; ?>" alt="<?php echo $nombre; ?>">
                <h3>Descripcion</h3>
                <p><?php echo $descripcion; ?></p>
            </div>
            <div class="todo">
                <h3><?php echo $nombre; ?></h3>
                <p>Precio: $<?php echo number_format($precio, 0, '.', ','); ?></p>
                <label for="talla">Talla:</label>
                <select id="talla" name="talla" required>
                    <option value="">Seleccione una talla</option>
                    <?php
                    if ($result_tallas->num_rows > 0) {
                        while ($row_talla = $result_tallas->fetch_assoc()) {
                            echo '<option value="' . $row_talla['cTalla'] . '">' . $row_talla['cTalla'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No hay tallas disponibles</option>';
                    }
                    ?>
                </select>

                <label for="color">Color:</label>
                <select id="color" name="color" required>
                    <option value="">Seleccione un color</option>
                    <?php
                    if ($result_colores->num_rows > 0) {
                        while ($row_color = $result_colores->fetch_assoc()) {
                            echo '<option value="' . $row_color['cColor'] . '">' . $row_color['cColor'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No hay colores disponibles</option>';
                    }
                    ?>
                </select>

                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" min="1" max="10" value="1" required>

                <button onclick="añadirAlCarrito()">Añadir al Carrito</button>

                <h4>Guia de Talla</h4>
                <img src="img/guia.jpg" alt="Guía de Talla" class="guia-talla">
            </div>
        </div>
        <script>
            function añadirAlCarrito() {
                // Obtener valores del producto
                var nombre = '<?php echo $nombre; ?>';
                var imagen = '<?php echo $imagen; ?>';
                var precio = <?php echo $precio; ?>;
                var talla = document.getElementById('talla').value;
                var color = document.getElementById('color').value;
                var cantidad = parseInt(document.getElementById('cantidad').value);

                // Eliminar mensajes previos si existen
                var mensajesPrevios = document.querySelectorAll('.mensaje-error');
                mensajesPrevios.forEach(mensaje => mensaje.remove());

                // Elemento donde mostrarás el mensaje
                var mensajeElemento = document.createElement('div');
                mensajeElemento.classList.add('mensaje-error');

                // Contenedor donde insertarás el mensaje (justo antes del botón)
                var contenedorMensaje = document.querySelector('.todo');
                var botonAnadir = document.querySelector('.todo button');

                // Validar selecciones con mensaje personalizado
                if (!talla) {
                    mensajeElemento.textContent = 'Por favor, selecciona una talla';
                    contenedorMensaje.insertBefore(mensajeElemento, botonAnadir);

                    // Agregar clase para mostrar con transición
                    setTimeout(() => {
                        mensajeElemento.classList.add('mostrar');
                    }, 10);

                    // Borrar mensaje después de 3 segundos
                    setTimeout(() => {
                        mensajeElemento.classList.remove('mostrar');
                        setTimeout(() => {
                            if (mensajeElemento.parentNode) {
                                mensajeElemento.parentNode.removeChild(mensajeElemento);
                            }
                        }, 300);
                    }, 3000);

                    return;
                }

                if (!color) {
                    mensajeElemento.textContent = 'Por favor, selecciona un color';
                    contenedorMensaje.insertBefore(mensajeElemento, botonAnadir);

                    // Agregar clase para mostrar con transición
                    setTimeout(() => {
                        mensajeElemento.classList.add('mostrar');
                    }, 10);

                    // Borrar mensaje después de 3 segundos
                    setTimeout(() => {
                        mensajeElemento.classList.remove('mostrar');
                        setTimeout(() => {
                            if (mensajeElemento.parentNode) {
                                mensajeElemento.parentNode.removeChild(mensajeElemento);
                            }
                        }, 300);
                    }, 3000);

                    return;
                }

                var carrito = JSON.parse(localStorage.getItem('carrito')) || [];

                var productoExistenteIndex = carrito.findIndex(p =>
                    p.nombre === nombre &&
                    p.talla === talla &&
                    p.color === color
                );

                if (productoExistenteIndex !== -1) {
                    carrito[productoExistenteIndex].cantidad += cantidad;
                } else {
                    var producto = {
                        nombre: nombre,
                        imagen: imagen,
                        precio: precio,
                        talla: talla,
                        color: color,
                        cantidad: cantidad
                    };
                    carrito.push(producto);
                }

                localStorage.setItem('carrito', JSON.stringify(carrito));

                actualizarContadorCarrito();
            }

            // Función para actualizar contador de carrito
            function actualizarContadorCarrito() {
                var carrito = JSON.parse(localStorage.getItem('carrito')) || [];
                var contadorCarrito = document.getElementById('contador-carrito');
                if (contadorCarrito) {
                    contadorCarrito.textContent = carrito.length;
                }
            }

            // Llamar a esta función cuando se carga la página
            window.onload = actualizarContadorCarrito;
        </script>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script nonce="randomString" src="./js/carrito.js"></script>
</body>

</html>