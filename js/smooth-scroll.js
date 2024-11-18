// Crear archivo: js/smooth-scroll.js
document.addEventListener('DOMContentLoaded', function() {
    // Obtener todos los enlaces internos que comienzan con #
    const internalLinks = document.querySelectorAll('a[href^="#"]');
    
    internalLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Prevenir el comportamiento predeterminado del enlace
            e.preventDefault();
            
            // Obtener el ID del elemento objetivo desde el href
            const targetId = this.getAttribute('href');
            
            // Verificar si estamos en index.php
            const isIndexPage = window.location.pathname.endsWith('index.php') || 
                              window.location.pathname.endsWith('/');
            
            if (isIndexPage) {
                // Si estamos en index.php, hacer scroll suave
                if (targetId === '#inicio') {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                } else {
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }
            } else {
                // Si no estamos en index.php, redirigir a index.php con el hash
                window.location.href = 'index.php' + targetId;
            }
        });
    });
});