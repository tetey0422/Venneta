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
        <div id="inicio" class="inicio">
            <h1>VENNETA</h1>
        </div>
        <div id="diseNos" class="diseNos">
            <h2>DISEÑOS</h2>
            <nav>
                <?php
                // Consulta para obtener los productos
                $sql = "SELECT cNombre, cImagen, nPrecio FROM TProducto LIMIT 3";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Salida de cada fila
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="diseNo">';
                        echo '<a href="anadir.php?nombre=' . urlencode($row["cNombre"]) . '&imagen=' . urlencode($row["cImagen"]) . '&precio=' . urlencode($row["nPrecio"]) . '">';
                        echo '<img src="' . $row["cImagen"] . '" alt="' . $row["cNombre"] . '">';
                        echo '<h3>' . $row["cNombre"] . '</h3>';
                        echo '<p>Precio: $' . number_format($row["nPrecio"], 0, '.', ',') . '</p>'; // Formato de precio
                        echo '</a>';
                        echo '</div>';
                    }
                } else {
                    echo "0 resultados";
                }

                $conn->close(); // Cerrar conexión
                ?>
            </nav>
            <button onclick="location.href='diseno.php';">VER MÁS</button>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="./js/scroll.js"></script>
    <script nonce="randomString" src="./js/carrito.js"></script>
</body>

</html>