<?php
require_once 'db_connect.php';
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Corrected prepared statement using ? placeholders
    $stmt = $pdo->prepare("SELECT u.nUsuarioID, u.cNombre_Usuario, u.cContraseña, c.cNombre 
                            FROM TUsuario u
                            LEFT JOIN TCliente c ON u.nUsuarioID = c.nUsuarioID
                            WHERE u.cEmail = ? OR u.cNombre_Usuario = ?");
    $stmt->execute([$usuario, $usuario]);
    $user = $stmt->fetch();

    if ($user && password_verify($contrasena, $user['cContraseña'])) {
        session_start();
        $_SESSION['user_id'] = $user['nUsuarioID'];
        $_SESSION['username'] = $user['cNombre_Usuario'];
        $_SESSION['nombre'] = $user['cNombre'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="descripcion" content="⚡TIENDA VIRTUAL⚡ ¡TODOS LOS DISEÑOS DISPONIBLES! ENVIOS A TODO COLOMBIA 🇨🇴">
    <meta name="keywords" content="ropa, tienda de ropa, moda, descuentos, ropa de hombre, ropa de mujer">
    <meta name="author" content="Tienda de Ropa Venneta">
    <link rel="icon" type="image/jpg" href="./img/venneta_logo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Jura:wght@300..700&display=swap">
    <title>Venneta - Iniciar Sesión</title>
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <main class="login-container">
        <a href="index.php">
            <img src="img/logo1.png" alt="Venneta">
        </a>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <input type="text" name="usuario" placeholder="USUARIO O CORREO ELECTRÓNICO" required>
            <div class="password-container">
                <input type="password" name="contrasena" id="password" placeholder="CONTRASEÑA" required>
                <span class="toggle-password" id="togglePassword">Mostrar</span>
            </div>
            <button type="submit">INICIAR SESIÓN</button>
        </form>
        <a href="crear-cuenta.php" class="create-account">CREAR CUENTA NUEVA</a>
    </main>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleText = this;

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleText.textContent = 'Ocultar'; // Cambia el texto a "Ocultar"
            } else {
                passwordInput.type = 'password';
                toggleText.textContent = 'Mostrar'; // Cambia el texto a "Mostrar"
            }
        });
    </script>
</body>

</html>