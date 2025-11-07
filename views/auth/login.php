<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Iniciar Sesión</h2>
            <p>Accede a tu cuenta para gestionar tus alojamientos</p>
        </div>

        <form method="POST" action="<?= UrlHelper::to('login') ?>" class="auth-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="tu@email.com"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Iniciar Sesión
            </button>
        </form>

        <div class="auth-footer">
            <p>¿No tienes cuenta? <a href="<?= UrlHelper::to('register') ?>">Regístrate aquí</a></p>
        </div>

        <!-- Credenciales de prueba -->
        <div class="test-credentials">
            <p><strong>Prueba con el administrador:</strong></p>
            <p>Email: admin@alojamientos.com</p>
            <p>Contraseña: admin123</p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>