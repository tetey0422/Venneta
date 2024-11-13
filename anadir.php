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
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Jura:wght@300..700&display=swap">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/config.php'; ?>
    <main>
    <?php
        if (isset($_GET['nombre']) && isset($_GET['imagen']) && isset($_GET['precio']) && isset($_GET['descripcion'])) {
            $nombre = urldecode($_GET['nombre']);
            $imagen = urldecode($_GET['imagen']);
            $precio = urldecode($_GET['precio']);
            $descripcion = urldecode($_GET['descripcion']);

            // Consulta para obtener las tallas disponibles
            $sql_tallas = "SELECT cTalla FROM TTalla_Producto tp JOIN TTalla t ON tp.nTallaID = t.nTallaID JOIN TProducto p ON tp.nProductoID = p.nProductoID WHERE p.cNombre = '$nombre'";
            $result_tallas = $conn->query($sql_tallas);

            // Consulta para obtener los colores disponibles
            $sql_colores = "SELECT cColor FROM TColor_Producto cp JOIN TColor c ON cp.nColorID = c.nColorID JOIN TProducto p ON cp.nProductoID = p.nProductoID WHERE p.cNombre = '$nombre'";
            $result_colores = $conn->query($sql_colores);
        } else {
            echo "Información del producto no disponible.";
            exit;
        }
        ?>
        <div class="producto">
            <img src="<?php echo $imagen; ?>" alt="<?php echo $nombre; ?>">
            <h3><?php echo $nombre; ?></h3>
            <p><?php echo $descripcion; ?></p>
            <p>Precio: $<?php echo number_format($precio, 0, '.', ','); ?></p>
            
            <!-- Mostrar las tallas disponibles -->
            <label for="talla">Talla:</label>
            <select id="talla" name="talla">
                <?php
                if ($result_tallas->num_rows > 0) {
                    while ($row_talla = $result_tallas->fetch_assoc()) {
                        echo '<option value="' . $row_talla['cTalla'] . '">' . $row_talla['cTalla'] . '</option>';
                    }
                } else {
                    echo '<option value="">No disponible</option>';
                }
                ?>
            </select>

            <!-- Mostrar los colores disponibles -->
            <label for="color">Color:</label>
            <select id="color" name="color">
                <?php
                if ($result_colores->num_rows > 0) {
                    while ($row_color = $result_colores->fetch_assoc()) {
                        echo '<option value="' . $row_color['cColor'] . '">' . $row_color['cColor'] . '</option>';
                    }
                } else {
                    echo '<option value="">No disponible</option>';
                }
                ?>
            </select>

            <!-- Seleccionar cantidad -->
            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" min="1" value="1">

            <!-- Botón para añadir al carrito -->
            <button onclick="añadirAlCarrito()">Añadir al Carrito</button>
        </div>

        <script>
            function añadirAlCarrito() {
                var talla = document.getElementById('talla').value;
                var color = document.getElementById('color').value;
                var cantidad = document.getElementById('cantidad').value;
                if (talla && color && cantidad > 0) {
                    // Aquí puedes agregar la lógica para añadir el producto al carrito
                    alert('Producto añadido al carrito: ' + '<?php echo $nombre; ?>' + ', Talla: ' + talla + ', Color: ' + color + ', Cantidad: ' + cantidad);
                } else {
                    alert('Por favor, selecciona una talla, un color y una cantidad válida.');
                }
            }
        </script>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>

</html>