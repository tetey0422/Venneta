## 🛍️ Venneta - Tienda de Ropa Virtual

Venneta es una aplicación web de comercio electrónico diseñada para ofrecer una experiencia de compra rápida, amigable e intuitiva. Los usuarios pueden explorar productos, gestionar su carrito de compras y completar transacciones en un entorno moderno y responsivo.

## 📋 Descripción del Proyecto

Venneta es una tienda virtual enfocada en la facilidad de navegación y la experiencia del usuario. Algunas de sus principales características incluyen:

Exploración de productos: Encuentra tus artículos favoritos fácilmente.
Carrito de compras: Añade, elimina y revisa los totales en tiempo real.
Interfaz moderna: Un diseño responsivo y atractivo para cualquier dispositivo.
Gestión eficiente de datos: Conexión a base de datos para productos y usuarios.

## 🚀 Tecnologías Utilizadas

Tecnología	Uso principal

HTML5	Estructura del contenido del sitio web.
CSS3	Estilos visuales y diseño responsivo.
JavaScript	Funcionalidad dinámica en el frontend.
PHP	Lógica del backend y conexión con la base de datos.
MySQL	Gestión de datos para usuarios y productos.
XAMPP	Servidor local para desarrollo y pruebas.

## 📂 Estructura del Proyecto

```plaintext
📂 venneta/
├── 📂 css/                # Archivos CSS para estilos
│   ├── anadir.css
│   ├── carrito.css
│   ├── crear-cuenta.css
│   ├── diseno.css
│   ├── footer.css
│   ├── header.css
│   ├── index.css
│   └── login.css
├── 📂 db/                 # Base de datos del proyecto  
│   └── venneta.sql
├── 📂 img/                # Imágenes del sitio
├── 📂 includes/           # Archivos PHP reutilizables (header, footer)
│   ├── config.php
│   ├── footer.php
│   └── header.php
├── 📂 js/                 # JavaScript para funcionalidad dinámica
│   ├── carrito.js
│   └── scroll.js
├── 📂 MER_ER/             # Modelo de datos
│   ├── MER.pdf
│   └── VennetaModelo.pdf
├── anadir.php           # Página de cada producto
├── carrito.php          # Página del carrito de compras
├── crear-cuenta.php     # Página para crear cuenta
├── db_connect.php       # Conexión de la base de datos
├── diseno.php           # Página de catálogo de productos
├── Documentacion.pdf           # Documento PDF
├── index.php            # Página principal
├── login.php            # Página de inicio de sesión 
├── obtener_usuario.php  # Página para q el sistema sepa cual es el usuario
└── README.md            # Archivo README del proyecto
```

## 📦 Instalación y Configuración

1️⃣ Requisitos previos
XAMPP (o un servidor local similar) instalado en tu máquina.
Navegador web actualizado.

2️⃣ Configurar la base de datos
Inicia XAMPP y activa los servicios de Apache y MySQL.
Abre http://localhost/phpmyadmin.
Crea una base de datos llamada bdvenneta.
Importa el archivo SQL ubicado en db/venneta.sql.

3️⃣ Configurar config.php
Actualiza las credenciales de la base de datos en includes/config.php:

```
php
Copiar código
<?php  
$servername = "localhost";  
$username = "root";  
$password = "";  
$dbname = "bdvenneta";  

$conn = new mysqli($servername, $username, $password, $dbname);  

if ($conn->connect_error) {  
    die("Conexión fallida: " . $conn->connect_error);  
}  
?>
```

4️⃣ Iniciar la aplicación
Coloca el proyecto dentro de la carpeta htdocs de XAMPP.
Accede al sitio en tu navegador: http://localhost/venneta/index.php.

## 📌 Funcionalidades Principales

Funcionalidad	Descripción
Página de inicio	Muestra productos destacados e información general.
Catálogo de productos	Lista artículos disponibles para su compra.
Carrito de compras	Gestiona productos añadidos, actualiza cantidades y elimina artículos.

## 📱 Responsividad

El diseño está optimizado para pantallas de escritorio y dispositivos móviles, garantizando una experiencia de usuario fluida en cualquier entorno.

## 🛠️ Futuras Mejoras

Implementar autenticación y registro avanzado de usuarios.
Integrar un sistema de historial de compras.
Añadir métodos de pago en línea mediante pasarelas como PayPal o Stripe.
Incorporar animaciones y transiciones para mejorar la experiencia visual.

## 📄 Licencia

Este proyecto está licenciado bajo la Licencia MIT. Consulta el archivo LICENSE para más detalles.

## 📧 Contacto

Si tienes preguntas o sugerencias, no dudes en contactarnos:

- GitHub (Jefrey): https://github.com/tetey0422
- GitHub (Valeria): https://github.com/yeyaaaaaa
- GitHub (Cristian): https://github.com/Itemt