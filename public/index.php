<?php
/**
 * Punto de entrada de la aplicación
 * Aquí se define el enrutamiento y se inicializa la aplicación
 */

// Iniciar sesión
session_start();

// Cargar clases necesarias
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';

// Conectar a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear router
$router = new Router($db);

// ============================================
// RUTAS PÚBLICAS
// ============================================
$router->get('/', 'AlojamientoController', 'index');
$router->get('/buscar', 'AlojamientoController', 'search');

// ============================================
// RUTAS DE AUTENTICACIÓN
// ============================================
$router->get('/login', 'AuthController', 'showLogin');
$router->post('/login', 'AuthController', 'login');
$router->get('/register', 'AuthController', 'showRegister');
$router->post('/register', 'AuthController', 'register');
$router->get('/logout', 'AuthController', 'logout');

// ============================================
// RUTAS DE USUARIO (requieren autenticación)
// ============================================
$router->get('/dashboard', 'UserController', 'dashboard');
$router->post('/alojamiento/select', 'AlojamientoController', 'select');
$router->post('/alojamiento/remove', 'AlojamientoController', 'removeSelection');

// ============================================
// RUTAS DE ADMINISTRADOR (requieren rol admin)
// ============================================
$router->get('/admin', 'UserController', 'adminPanel');
$router->post('/admin/alojamiento/create', 'AlojamientoController', 'create');

// Ejecutar el router
$router->run();