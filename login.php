<?php
require_once 'db_connect.php';
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $stmt = $pdo->prepare("SELECT nUsuarioID, cNombre_Usuario, cContraseña FROM TUsuario WHERE cEmail = :usuario OR cNombre_Usuario = :usuario");
    $stmt->execute(['usuario' => $usuario]);
    $user = $stmt->fetch();
    if ($user && password_verify($contrasena, $user['cContraseña'])) {
        session_start();
        $_SESSION['user_id'] = $user['nUsuarioID'];
        $_SESSION['username'] = $user['cNombre_Usuario'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es-CO">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="descripcion" content="⚡TIENDA VIRTUAL⚡ ¡TODOS LOS DISEÑOS DISPONIBLES! ENVIOS A TODO COLOMBIA 🇨🇴">
    <meta name="keywords" content="ropa, tienda de ropa, moda, descuentos, ropa de hombre, ropa de mujer">
    <meta name="author" content="Tienda de Ropa Venneta">
    <link rel="icon" type="image/jpg" href="./img/venneta_logo.png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Jura:wght@300..700&display=swap">
    <title>Venneta - Iniciar Sesión</title>
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <main class="login-container">
        <img src="img/logo1.png" alt="Venneta">
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <input type="text" name="usuario" placeholder="USUARIO O CORREO ELECTRÓNICO" required>
            <div class="password-container">
                <input type="password" name="contrasena" id="password" placeholder="CONTRASEÑA" required>
                </button>
            </div>
            <button type="submit">INICIAR SESIÓN</button>
        </form>
        <a href="olvide-contrasena.php" class="forgot-password">¿OLVIDASTE TU CONTRASEÑA?</a>
        <a href="crear-cuenta.php" class="btn-crear-cuenta">CREAR CUENTA NUEVA</a>
    </main>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

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