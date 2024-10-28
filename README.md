
# Venneta - Tienda de Ropa Virtual

Venneta es una aplicación web de e-commerce para una tienda de ropa en línea. Permite a los usuarios explorar una colección de productos, añadir artículos al carrito y realizar una compra de manera intuitiva y atractiva.

## 📋 Descripción del Proyecto

Venneta es una tienda virtual que ofrece una experiencia de compra rápida y amigable, diseñada para ser responsiva y fácil de navegar. Incluye una interfaz de usuario moderna con funcionalidades como:
- Visualización de productos.
- Carrito de compras que permite añadir, quitar y ver el total de la compra.
- Sistema de navegación claro y bien estructurado.
- Conexión a una base de datos para gestionar productos y usuarios.

## 🚀 Tecnologías Utilizadas

- **HTML5** - Estructura básica del proyecto.
- **CSS3** - Estilos y diseño responsivo.
- **JavaScript** - Funcionalidad dinámica en el frontend.
- **PHP** - Lógica en el backend, manejo de sesiones y conexión con la base de datos.
- **MySQL** - Base de datos para almacenar productos, usuarios y el carrito.
- **XAMPP** - Servidor local para desarrollo y pruebas de PHP y MySQL.

## 📂 Estructura del Proyecto

```plaintext
venneta/
├── css/                # Archivos CSS para estilos
│   └── estilos.css
├── img/                # Imágenes del sitio
├── js/                 # JavaScript para funcionalidad dinámica
│   └── scripts.js
├── includes/           # Archivos PHP reutilizables (header, footer)
│   ├── header.php
│   └── footer.php
│   └── config.php
├── index.php           # Página principal
├── productos.php       # Página de productos
├── carrito.php         # Página del carrito de compras
└── contacto.php        # Página de contacto
```
## 📦 Instalación y Configuración

Descargar e Instalar XAMPP:

Descarga e instala XAMPP para correr un servidor local.
Inicia Apache y MySQL desde el panel de control de XAMPP.
Configurar la Base de Datos:

Abre phpMyAdmin en http://localhost/phpmyadmin.
Crea una base de datos llamada tienda_ropa.
Importa el archivo SQL (si tienes uno) o crea las tablas manualmente.
Configurar config.php:

Asegúrate de que el archivo config.php tenga las credenciales de tu base de datos:

```plaintext
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bdvenneta";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
```
Iniciar la Aplicación:

Guarda todos los archivos en la carpeta htdocs de XAMPP.
Accede a la aplicación en el navegador en http://localhost/venneta/index.php.

## 📌 Funcionalidades Principales

Página de Inicio:
Muestra una introducción a la tienda y productos destacados.
Página de Productos:
Lista los productos disponibles y permite añadirlos al carrito.
Carrito de Compras:
Permite ver y gestionar productos en el carrito con opciones para actualizar cantidades o eliminar productos.
Página de Contacto:
Permite a los usuarios ponerse en contacto con la tienda.

## 📱 Responsividad

El diseño está optimizado para pantallas de dispositivos móviles y de escritorio, proporcionando una experiencia de usuario amigable en cualquier dispositivo.

## 🛠️ Futuras Mejoras

Agregar un sistema de autenticación para usuarios registrados.
Implementar un historial de compras.
Integrar métodos de pago en línea.
Mejora del diseño de interfaz de usuario con animaciones y transiciones.

## 📄 Licencia

Este proyecto está bajo la Licencia MIT - consulta el archivo LICENSE para más detalles.

## 📧 Contacto

¡No dudes en comunicarte con nosotros si tienes alguna pregunta o comentario!
- GitHub : https://github.com/tetey0422
- GitHub : https://github.com/Itemt
- GitHub : https://github.com/yeyaaaaaa

### Explicación Final

Este archivo `README.md` es claro, conciso y estructurado.
- **Descripción del Proyecto**: Explica en qué consiste "Venneta".
- **Tecnologías Utilizadas**: Lista todas las tecnologías que usaste.
- **Estructura del Proyecto**: Proporciona una visión de la organización de archivos y carpetas.
- **Instalación y Configuración**: Guía al usuario para configurar el proyecto en su entorno.
- **Funcionalidades Principales**: Enumera las características clave de la tienda.
- **Responsividad**: Menciona que el diseño es adaptable a diferentes dispositivos.
- **Futuras Mejoras**: Ideas para expandir y mejorar el proyecto en el futuro.
- **Licencia y Autor**: Indica los derechos de uso y quién desarrolló el proyecto.
