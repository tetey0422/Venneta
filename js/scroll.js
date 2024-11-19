// js/scroll.js
document.addEventListener('DOMContentLoaded', function () {
    // Obtener todos los enlaces con la clase scroll-link
    const scrollLinks = document.querySelectorAll('.scroll-link');

    scrollLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            // Solo prevenir el comportamiento predeterminado si estamos en index.php
            if (window.location.pathname.endsWith('http://localhost/venneta/index.php') ||
                window.location.pathname.endsWith('http://localhost/venneta/')) {
                e.preventDefault();

                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
});