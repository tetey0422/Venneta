document.addEventListener('DOMContentLoaded', function () {
    // Obtener todos los enlaces con scroll-link, logo, e inicio
    const scrollLinks = document.querySelectorAll('.scroll-link, a[href="./index.php"], a[href="./"');

    scrollLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            const currentPath = window.location.pathname;
            const isIndexPage = currentPath.endsWith('index.php') ||
                currentPath.endsWith('/venneta/') ||
                currentPath.endsWith('/venneta');

            if (isIndexPage) {
                // Si estamos en index.php, hacer scroll suave
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            } else {
                // Si estamos en otra página, redirigir a index.php
                window.location.href = './index.php';
            }
        });
    });
});