<?php
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $documento = $_POST['documento'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar_contrasena = $_POST['confirmar_contrasena'] ?? '';

    if (empty($nombre) || empty($apellido) || empty($documento) || empty($email) || empty($telefono) || empty($usuario) || empty($contrasena) || empty($confirmar_contrasena)) {
        $error = "Por favor, complete todos los campos obligatorios.";
    } elseif ($contrasena !== $confirmar_contrasena) {
        $error = "Las contraseñas no coinciden.";
    }

    if (empty($error)) {
        require_once 'db_connect.php';

        try {
            // Start a transaction
            $pdo->beginTransaction();

            // Check if email or username already exists
            $stmt = $pdo->prepare("SELECT * FROM TUsuario WHERE cEmail = :email OR cNombre_Usuario = :usuario");
            $stmt->execute(['email' => $email, 'usuario' => $usuario]);
            if ($stmt->rowCount() > 0) {
                $error = "El correo electrónico o el nombre de usuario ya están en uso.";
                throw new Exception($error);
            }

            // Insert into TUsuario first
            $stmt = $pdo->prepare("INSERT INTO TUsuario (cEmail, cNombre_Usuario, cContraseña) VALUES (:email, :usuario, :contrasena)");
            $stmt->execute([
                'email' => $email,
                'usuario' => $usuario,
                'contrasena' => password_hash($contrasena, PASSWORD_DEFAULT)
            ]);

            // Get the last inserted user ID
            $usuarioID = $pdo->lastInsertId();

            // Insert into TCliente
            $stmt = $pdo->prepare("INSERT INTO TCliente (cNombre, cApellido, cDocumento, cEmail, cTelefono, nUsuarioID) VALUES (:nombre, :apellido, :documento, :email, :telefono, :usuarioID)");
            $stmt->execute([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'documento' => $documento,
                'email' => $email,
                'telefono' => $telefono,
                'usuarioID' => $usuarioID
            ]);

            // Commit the transaction
            $pdo->commit();

            header("Location: login.php");
            exit();
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            $pdo->rollBack();
            $error = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Venneta - Crear Cuenta</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="descripcion" content="⚡TIENDA VIRTUAL⚡ ¡TODOS LOS DISEÑOS DISPONIBLES! ENVIOS A TODO COLOMBIA 🇨🇴">
    <meta name="keywords" content="ropa, tienda de ropa, moda, descuentos, ropa de hombre, ropa de mujer">
    <meta name="author" content="Tienda de Ropa Venneta">
    <link rel="icon" type="image/jpg" href="./img/venneta_logo.png">
    <link rel="stylesheet" href="./css/crear-cuenta.css">
</head>

<body>
    <main class="login-container">
        <a href="index.php">
            <img src="img/logo1.png" alt="Venneta">
        </a>

        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="crear-cuenta.php" method="post">
            <input type="text" name="nombre" placeholder="NOMBRE" required>
            <input type="text" name="apellido" placeholder="APELLIDO" required>
            <input type="text" name="documento" placeholder="DOCUMENTO" required>
            <input type="email" name="email" placeholder="CORREO ELECTRÓNICO" required>
            <input type="tel" name="telefono" placeholder="TELÉFONO" required>
            <input type="text" name="usuario" placeholder="NOMBRE DE USUARIO" required>
            <input type="password" name="contrasena" placeholder="CONTRASEÑA" required>
            <input type="password" name="confirmar_contrasena" placeholder="CONFIRMAR CONTRASEÑA" required>
            <button type="submit">Crear Cuenta</button>
        </form>

        <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </main>
</body>

</html>