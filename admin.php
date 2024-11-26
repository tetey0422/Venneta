<?php
session_start();
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = floatval($_POST['precio'] ?? 0);
    $categoriaID = intval($_POST['categoriaID'] ?? 0);
    $imagen = $_FILES['imagen']['name'] ?? '';

    // Subir la imagen del producto
    if (!empty($imagen)) {
        $rutaImagen = 'uploads/' . basename($imagen);
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
            die("Error al subir la imagen.");
        }
    } else {
        $rutaImagen = null;
    }

    // Insertar el producto
    try {
        $pdo->beginTransaction();

        // Insertar en TProducto
        $stmtProducto = $pdo->prepare("INSERT INTO TProducto (cNombre, cDescripcion, nPrecio, nStock, cImagen, nCategoriaID) 
                                       VALUES (:nombre, :descripcion, :precio, 0, :imagen, :categoriaID)");
        $stmtProducto->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':imagen' => $rutaImagen,
            ':categoriaID' => $categoriaID
        ]);

        // Obtener el ID del producto insertado
        $productoID = $pdo->lastInsertId();

        // Insertar combinaciones de tallas, colores y cantidades
        $totalStock = 0;

        // Verificar si se enviaron combinaciones de tallas y colores
        if (isset($_POST['cantidad']) && is_array($_POST['cantidad'])) {
            foreach ($_POST['cantidad'] as $tallaID => $colores) {
                foreach ($colores as $colorID => $cantidad) {
                    $cantidad = intval($cantidad);
                    if ($cantidad > 0) {
                        $stmtDetalle = $pdo->prepare("INSERT INTO TTalla_Color_Producto (nProductoID, nTallaID, nColorID, nCantidad) 
                                                      VALUES (:productoID, :tallaID, :colorID, :cantidad)");
                        $stmtDetalle->execute([
                            ':productoID' => $productoID,
                            ':tallaID' => $tallaID,
                            ':colorID' => $colorID,
                            ':cantidad' => $cantidad
                        ]);

                        // Sumar al stock total
                        $totalStock += $cantidad;
                    }
                }
            }
        }

        $pdo->commit();
        echo "Producto agregado exitosamente con un stock total de $totalStock.";
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error al agregar el producto: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venneta - Subir Producto</title>
    <link rel="stylesheet" href="./css/admin.css">
</head>

<body>
    <div class="container">
        <h1>Subir Nuevo Producto</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre del Producto</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" required></textarea>
            </div>

            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="number" id="precio" name="precio" required min="0" step="1">
            </div>

            <div class="form-group">
                <label for="categoriaID">Categoría</label>
                <select id="categoriaID" name="categoriaID" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['nCategoriaID']; ?>">
                            <?php echo htmlspecialchars($category['cNombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen Principal</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required>
            </div>

            <div class="form-group">
                <h3>Tallas y Colores</h3>
                <div class="size-color-grid">
                    <?php foreach ($sizes as $size): ?>
                        <div class="size-column">
                            <h4><?php echo htmlspecialchars($size['cTalla']); ?></h4>
                            <?php foreach ($colors as $color): ?>
                                <div class="color-row">
                                    <input type="checkbox"
                                        id="talla-<?php echo $size['nTallaID']; ?>-color-<?php echo $color['nColorID']; ?>"
                                        name="tallas[]"
                                        value="<?php echo $size['nTallaID']; ?>"
                                        class="size-checkbox"
                                        data-size="<?php echo $size['nTallaID']; ?>"
                                        data-color="<?php echo $color['nColorID']; ?>">
                                    <label for="talla-<?php echo $size['nTallaID']; ?>-color-<?php echo $color['nColorID']; ?>">
                                        <?php echo htmlspecialchars($color['cColor']); ?>
                                    </label>
                                    <input type="number"
                                        name="cantidad[<?php echo $size['nTallaID']; ?>][<?php echo $color['nColorID']; ?>]"
                                        min="0"
                                        placeholder="Cantidad"
                                        class="cantidad-input"
                                        data-size="<?php echo $size['nTallaID']; ?>"
                                        data-color="<?php echo $color['nColorID']; ?>"
                                        disabled>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit">Subir Producto</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const sizeCheckboxes = document.querySelectorAll('.size-checkbox');
            const quantityInputs = document.querySelectorAll('.cantidad-input');

            sizeCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const sizeId = this.getAttribute('data-size');
                    const colorId = this.getAttribute('data-color');
                    const quantityInput = document.querySelector(`.cantidad-input[data-size="${sizeId}"][data-color="${colorId}"]`);

                    if (this.checked) {
                        quantityInput.disabled = false;
                        quantityInput.required = true;
                    } else {
                        quantityInput.disabled = true;
                        quantityInput.required = false;
                        quantityInput.value = '';
                    }
                });
            });

            form.addEventListener('submit', function(event) {
                let isValid = true;
                const selectedCombinations = [];

                sizeCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const sizeId = checkbox.getAttribute('data-size');
                        const colorId = checkbox.getAttribute('data-color');
                        const quantityInput = document.querySelector(`.cantidad-input[data-size="${sizeId}"][data-color="${colorId}"]`);

                        // Ensure quantity is a positive integer
                        const quantity = parseInt(quantityInput.value, 10);

                        if (isNaN(quantity) || quantity < 1) {
                            isValid = false;
                            alert(`Por favor, ingrese una cantidad válida (mayor que 0) para la talla ${sizeId} y color ${colorId}`);
                            quantityInput.focus();
                        } else {
                            // Ensure the quantity input is included in the form submission
                            quantityInput.disabled = false;
                            selectedCombinations.push({
                                size: sizeId,
                                color: colorId,
                                quantity: quantity
                            });
                        }
                    }
                });

                if (!isValid || selectedCombinations.length === 0) {
                    event.preventDefault();
                    alert('Debe seleccionar al menos una combinación de talla y color con una cantidad válida (mayor que 0).');
                }
            });
        });
    </script>
</body>

</html>