<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<header id="inicio">
    <link rel="stylesheet" href="./css/header.css">
    <nav>
        <div class="hp1">
            <a href="./index.php"><img src="./img/LOGO.png" alt="Logo de Venneta"></a>
            <a href="./index.php">Inicio</a>
            <a href="./diseno.php">Diseños</a>
            <a href="#contacto">contacto</a>
        </div>
        <div class="hp2">
            <?php if (!$is_logged_in): ?>
                <a href="./login.php" aria-label="Perfil de usuario"><img src="./img/image_2.png" alt="usuario"></a>
            <?php endif; ?>
            <button id="carrito-btn">
                <img src="./img/image.png" alt="carrito de compras">
                <span id="contador-carrito" class="hidden">0</span>
            </button>
            <div id="carrito">
                <h3>Tu carrito</h3>
            </div>
        </div>
        </div>
    </nav>
</header>