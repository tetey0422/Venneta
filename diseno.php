<!DOCTYPE html>
<html lang="es-CO">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="descripcion" content="⚡TIENDA VIRTUAL⚡ ¡TODOS LOS DISEÑOS DISPONIBLES! ENVIOS A TODO COLOMBIA 🇨🇴">
    <meta name="keywords" content="ropa, tienda de ropa, moda, descuentos, ropa de hombre, ropa de mujer">
    <meta name="author" content="Tienda de Ropa Venneta">
    <title>Venneta - Diseño</title>
    <link rel="icon" type="image/jpg" href="./img/venneta_logo.png">
    <link rel="stylesheet" href="./css/diseno.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Jura:wght@300..700&display=swap">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/config.php'; ?>
    <main>
        <div class="filtros">
            <form method="GET" action="diseno.php">
                <!-- Filtro de Talla -->
                <label for="talla">Talla:</label>
                <select id="talla" name="talla">
                    <option value="">Todas</option>
                    <?php
                    $sql_tallas = "SELECT DISTINCT cTalla FROM TTalla";
                    $result_tallas = $conn->query($sql_tallas);
                    if ($result_tallas->num_rows > 0) {
                        while ($row_talla = $result_tallas->fetch_assoc()) {
                            echo '<option value="' . $row_talla['cTalla'] . '">' . $row_talla['cTalla'] . '</option>';
                        }
                    }
                    ?>
                </select>

                <!-- Filtro de Color -->
                <label for="color">Color:</label>
                <select id="color" name="color">
                    <option value="">Todos</option>
                    <?php
                    $sql_colores = "SELECT DISTINCT cColor FROM TColor";
                    $result_colores = $conn->query($sql_colores);
                    if ($result_colores->num_rows > 0) {
                        while ($row_color = $result_colores->fetch_assoc()) {
                            echo '<option value="' . $row_color['cColor'] . '">' . $row_color['cColor'] . '</option>';
                        }
                    }
                    ?>
                </select>

                <button type="submit">Filtrar</button>
            </form>
        </div>

        <div class="productos">
            <?php
            $filtro_talla = isset($_GET['talla']) ? $_GET['talla'] : '';
            $filtro_color = isset($_GET['color']) ? $_GET['color'] : '';

            $sql_productos = "SELECT p.cNombre, p.cImagen, p.nPrecio, p.cDescripcion 
                              FROM TProducto p 
                              JOIN TTalla_Producto tp ON p.nProductoID = tp.nProductoID 
                              JOIN TColor_Producto cp ON p.nProductoID = cp.nProductoID 
                              WHERE 1=1";

            if ($filtro_talla != '') {
                $sql_productos .= " AND tp.nTallaID = (SELECT nTallaID FROM TTalla WHERE cTalla = '$filtro_talla')";
            }

            if ($filtro_color != '') {
                $sql_productos .= " AND cp.nColorID = (SELECT nColorID FROM TColor WHERE cColor = '$filtro_color')";
            }

            $result_productos = $conn->query($sql_productos);

            if ($result_productos->num_rows > 0) {
                while ($row_producto = $result_productos->fetch_assoc()) {
                    echo '<div class="producto">';
                    echo '<img src="' . $row_producto['cImagen'] . '" alt="' . $row_producto['cNombre'] . '">';
                    echo '<h3>' . $row_producto['cNombre'] . '</h3>';
                    echo '<p>' . $row_producto['cDescripcion'] . '</p>';
                    echo '<p>Precio: $' . number_format($row_producto['nPrecio'], 0, '.', ',') . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No se encontraron productos con los filtros seleccionados.</p>';
            }
            ?>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="./js/carrito.js"></script>
</body>

</html>