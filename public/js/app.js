/**
 * Aplicación de Alojamientos - JavaScript Principal
 */

document.addEventListener('DOMContentLoaded', function() {
    // Auto-cerrar alertas después de 5 segundos
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Validación de formularios
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#EF4444';
                } else {
                    field.style.borderColor = '#E5E7EB';
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Por favor, completa todos los campos requeridos');
            }
        });
    });

    // Confirmación de eliminación
    const deleteForms = document.querySelectorAll('form[action*="remove"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('¿Estás seguro de que deseas eliminar este alojamiento de tus selecciones?')) {
                e.preventDefault();
            }
        });
    });

    // Smooth scroll para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Preview de imagen en formulario de admin
    const imageInput = document.getElementById('imagen');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            // Aquí podrías agregar lógica para preview de imagen
            console.log('URL de imagen:', this.value);
        });
    }

    // Validación de email
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.style.borderColor = '#EF4444';
                showTooltip(this, 'Por favor, ingresa un email válido');
            } else {
                this.style.borderColor = '#E5E7EB';
                hideTooltip(this);
            }
        });
    });

    // Validación de contraseña
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    const passwordConfirm = document.getElementById('password_confirm');
    
    if (passwordConfirm) {
        passwordConfirm.addEventListener('blur', function() {
            const password = document.getElementById('password');
            if (this.value && password.value !== this.value) {
                this.style.borderColor = '#EF4444';
                showTooltip(this, 'Las contraseñas no coinciden');
            } else {
                this.style.borderColor = '#E5E7EB';
                hideTooltip(this);
            }
        });
    }

    // Funciones auxiliares
    function showTooltip(element, message) {
        hideTooltip(element);
        const tooltip = document.createElement('div');
        tooltip.className = 'error-tooltip';
        tooltip.textContent = message;
        tooltip.style.cssText = `
            position: absolute;
            background: #EF4444;
            color: white;
            padding: 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            z-index: 1000;
        `;
        element.parentNode.style.position = 'relative';
        element.parentNode.appendChild(tooltip);
    }

    function hideTooltip(element) {
        const tooltip = element.parentNode.querySelector('.error-tooltip');
        if (tooltip) {
            tooltip.remove();
        }
    }

    // Loading state para formularios
    forms.forEach(form => {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            form.addEventListener('submit', function() {
                submitButton.disabled = true;
                submitButton.textContent = 'Procesando...';
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.textContent = submitButton.getAttribute('data-original-text') || 'Enviar';
                }, 3000);
            });
        }
    });

    // Filtro en tiempo real (opcional)
    const searchInput = document.querySelector('input[name="q"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Aquí podrías implementar búsqueda en tiempo real con AJAX
                console.log('Buscando:', this.value);
            }, 500);
        });
    }

    console.log('✓ Aplicación de Alojamientos cargada correctamente');
});