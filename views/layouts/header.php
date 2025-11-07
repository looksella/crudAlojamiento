<?php
Session::start();
$isAuthenticated = Session::isAuthenticated();
$isAdmin = Session::isAdmin();
$userName = Session::get('user_name', 'Usuario');
$flashSuccess = Session::getFlash('success');
$flashError = Session::getFlash('error');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Alojamientos' ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/">🏠 Alojamientos</a>
                </div>
                
                <nav class="nav">
                    <?php if ($isAuthenticated): ?>
                        <span class="user-info">
                            Hola, <?= htmlspecialchars($userName) ?>
                            <?php if ($isAdmin): ?>
                                <span class="badge badge-admin">Admin</span>
                            <?php endif; ?>
                        </span>
                        
                        <?php if ($isAdmin): ?>
                            <a href="/admin" class="btn btn-secondary">Panel Admin</a>
                        <?php else: ?>
                            <a href="/dashboard" class="btn btn-secondary">Mi Cuenta</a>
                        <?php endif; ?>
                        
                        <a href="/logout" class="btn btn-outline">Cerrar Sesión</a>
                    <?php else: ?>
                        <a href="/register" class="btn btn-secondary">Registrarse</a>
                        <a href="/login" class="btn btn-primary">Iniciar Sesión</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <!-- Mensajes Flash -->
    <?php if ($flashSuccess): ?>
        <div class="alert alert-success">
            <div class="container">
                ✓ <?= htmlspecialchars($flashSuccess) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($flashError): ?>
        <div class="alert alert-error">
            <div class="container">
                ✕ <?= htmlspecialchars($flashError) ?>
            </div>
        </div>
    <?php endif; ?>

    <main class="main-content">