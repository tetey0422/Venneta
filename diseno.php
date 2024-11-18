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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Jura:wght@300..700&display=swap">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/config.php'; ?>
    <main>
        <div class="contenedor-principal">
            <div class="filtros">
                <form method="GET" action="diseno.php">
                    <div class="filtro-grupo">
                        <label for="talla">TALLA</label>
                        <select id="talla" name="talla">
                            <option value="">Todas</option>
                            <?php
                            $talla_seleccionada = isset($_GET['talla']) ? $_GET['talla'] : '';
                            $sql_tallas = "SELECT DISTINCT cTalla FROM TTalla";
                            $result_tallas = $conn->query($sql_tallas);
                            if ($result_tallas->num_rows > 0) {
                                while ($row_talla = $result_tallas->fetch_assoc()) {
                                    $selected = ($talla_seleccionada === $row_talla['cTalla']) ? 'selected' : '';
                                    echo '<option value="' . $row_talla['cTalla'] . '" ' . $selected . '>' . $row_talla['cTalla'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="filtro-grupo">
                        <label for="color">COLOR</label>
                        <select id="color" name="color">
                            <option value="">Todos</option>
                            <?php
                            $color_seleccionado = isset($_GET['color']) ? $_GET['color'] : '';
                            $sql_colores = "SELECT DISTINCT cColor FROM TColor";
                            $result_colores = $conn->query($sql_colores);
                            if ($result_colores->num_rows > 0) {
                                while ($row_color = $result_colores->fetch_assoc()) {
                                    $selected = ($color_seleccionado === $row_color['cColor']) ? 'selected' : '';
                                    echo '<option value="' . $row_color['cColor'] . '" ' . $selected . '>' . $row_color['cColor'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <button type="submit">Filtrar</button>
                </form>
            </div>

            <div class="diseNos">
                <?php
                // Verificar si hay filtros aplicados
                $talla_filtro = !empty($_GET['talla']);
                $color_filtro = !empty($_GET['color']);

                if ($talla_filtro || $color_filtro) {
                    // Consulta con filtros
                    $sql = "SELECT DISTINCT p.nProductoID, p.cNombre, tcp.cImagen, p.nPrecio 
                            FROM TProducto p
                            JOIN TTalla_Color_Producto tcp ON p.nProductoID = tcp.nProductoID
                            JOIN TTalla t ON tcp.nTallaID = t.nTallaID
                            JOIN TColor c ON tcp.nColorID = c.nColorID
                            WHERE tcp.nCantidad > 0";

                    $params = array();
                    $types = "";

                    if ($talla_filtro) {
                        $sql .= " AND t.cTalla = ?";
                        $params[] = $_GET['talla'];
                        $types .= "s";
                    }
                    if ($color_filtro) {
                        $sql .= " AND c.cColor = ?";
                        $params[] = $_GET['color'];
                        $types .= "s";
                    }
                } else {
                    // Consulta sin filtros - muestra solo una imagen por producto
                    $sql = "SELECT DISTINCT p.nProductoID, p.cNombre, p.cImagen, p.nPrecio 
                            FROM TProducto p
                            WHERE p.nStock > 0";
                }

                // Agrupar por producto
                $sql .= " GROUP BY p.nProductoID";

                // Preparar y ejecutar la consulta
                $stmt = $conn->prepare($sql);
                
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="diseNo">';
                        echo '<a href="anadir.php?nombre=' . urlencode($row["cNombre"]) . '&imagen=' . urlencode($row["cImagen"]) . '&precio=' . urlencode($row["nPrecio"]) . '">';
                        echo '<img src="' . $row["cImagen"] . '" alt="' . $row["cNombre"] . '">';
                        echo '<h3>' . $row["cNombre"] . '</h3>';
                        echo '<p>Precio: $' . number_format($row["nPrecio"], 0, '.', ',') . '</p>';
                        echo '</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="no-resultados">No se encontraron productos con los filtros seleccionados.</p>';
                }

                $stmt->close();
                $conn->close();
                ?>
            </div>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="./js/carrito.js"></script>
</body>

</html>