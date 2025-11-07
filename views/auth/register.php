<?php 
require_once __DIR__ . '/../layouts/header.php'; 
$oldNombre = Session::get('old_nombre', '');
$oldEmail = Session::get('old_email', '');
Session::delete('old_nombre');
Session::delete('old_email');
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Crear Cuenta</h2>
            <p>Regístrate para empezar a seleccionar alojamientos</p>
        </div>

        <form method="POST" action="/register" class="auth-form">
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input 
                    type="text" 
                    id="nombre" 
                    name="nombre" 
                    placeholder="Tu nombre"
                    value="<?= htmlspecialchars($oldNombre) ?>"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="tu@email.com"
                    value="<?= htmlspecialchars($oldEmail) ?>"
                    required
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
                <small>Mínimo 6 caracteres</small>
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmar Contraseña</label>
                <input 
                    type="password" 
                    id="password_confirm" 
                    name="password_confirm" 
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Crear Cuenta
            </button>
        </form>

        <div class="auth-footer">
            <p>¿Ya tienes cuenta? <a href="/login">Inicia sesión aquí</a></p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>