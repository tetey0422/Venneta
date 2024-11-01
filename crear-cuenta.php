<?php
require_once 'db_connect.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $documento = $_POST['documento'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Verificar si las contraseñas coinciden
    if ($contrasena !== $confirmar_contrasena) {
        $error = "Las contraseñas no coinciden";
    } else {
        try {
            // Iniciar transacción
            $pdo->beginTransaction();

            // Verificar si el email ya existe en TUsuario
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM TUsuario WHERE cEmail = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $error = "Este correo electrónico ya está registrado";
            } else {
                // Crear el usuario primero
                $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO TUsuario (cEmail, cNombre_Usuario, cContraseña) VALUES (?, ?, ?)");
                $stmt->execute([$email, $usuario, $hashed_password]);

                // Obtener el ID del usuario recién creado
                $usuarioID = $pdo->lastInsertId();

                // Crear el cliente asociado
                $stmt = $pdo->prepare("INSERT INTO TCliente (cNombre, cApellido, cDocumento, cEmail, cTelefono, nUsuarioID) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nombre, $apellido, $documento, $email, $telefono, $usuarioID]);

                // Confirmar la transacción
                $pdo->commit();

                // Redirigir al login
                header("Location: login.php?registro=exitoso");
                exit();
            }
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $pdo->rollBack();
            $error = "Error al crear la cuenta. Por favor, intente nuevamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es-CO">

<head>
    <title>Venneta - Crear Cuenta</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="descripcion" content="⚡TIENDA VIRTUAL⚡ ¡TODOS LOS DISEÑOS DISPONIBLES! ENVIOS A TODO COLOMBIA 🇨🇴">
    <meta name="keywords" content="ropa, tienda de ropa, moda, descuentos, ropa de hombre, ropa de mujer">
    <meta name="author" content="Tienda de Ropa Venneta">
    <link rel="icon" type="image/jpg" href="./img/venneta_logo.png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Jura:wght@300..700&display=swap">
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <main class="login-container">
        <a href="./index.php"><img src="img/logo1.png" alt="Venneta"></a>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="crear-cuenta.php" method="post">
            <input type="text" name="nombre" placeholder="NOMBRE" required>
            <input type="text" name="apellido" placeholder="APELLIDO" required>
            <input type="text" name="documento" placeholder="DOCUMENTO" required>
            <input type="email" name="email" placeholder="CORREO ELECTRÓNICO" required>
            <input type="tel" name="telefono" placeholder="TELÉFONO" required>
            <input type="text" name="usuario" placeholder="NOMBRE DE USUARIO" required>
            <div class="password-container">
                <input type="password" name="contrasena" id="password" placeholder="CONTRASEÑA" required>
                <button type="button" class="toggle-password" onclick="togglePassword('password')">
                    <img src="img/eye.svg" alt="Toggle password" id="password-toggle">
                </button>
            </div>
            <div class="password-container">
                <input type="password" name="confirmar_contrasena" id="confirm-password" placeholder="CONFIRMAR CONTRASEÑA" required>
                <button type="button" class="toggle-password" onclick="togglePassword('confirm-password')">
                    <img src="img/eye.svg" alt="Toggle password" id="confirm-password-toggle">
                </button>
            </div>
            <button type="submit">CREAR CUENTA</button>
        </form>
        <a href="login.php" class="btn-crear-cuenta" style="margin-top: 20px;">VOLVER AL LOGIN</a>
    </main>

    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(inputId + '-toggle');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.src = 'img/eye-off.svg';
            } else {
                passwordInput.type = 'password';
                toggleIcon.src = 'img/eye.svg';
            }
        }
    </script>
</body>

</html>