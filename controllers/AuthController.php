<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';

class AuthController extends Controller {
    private $db;
    private $userModel;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
    }

    public function showLogin() {
        // Si ya está autenticado, redirigir
        if (Session::isAuthenticated()) {
            if (Session::isAdmin()) {
                $this->redirect('admin');
            } else {
                $this->redirect('dashboard');
            }
            return;
        }

        $this->view('auth/login', [
            'title' => 'Iniciar Sesión'
        ]);
    }

    public function login() {
        if (!$this->isPost()) {
            $this->redirect('login');
            return;
        }

        $email = $this->post('email');
        $password = $this->post('password');

        // Validar datos
        $validator = new Validator();
        $validator->required('email', $email, 'El email es requerido')
                  ->email('email', $email, 'Email inválido')
                  ->required('password', $password, 'La contraseña es requerida');

        if ($validator->fails()) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('login');
            return;
        }

        // Verificar credenciales
        $user = $this->userModel->verifyCredentials($email, $password);

        if (!$user) {
            Session::setFlash('error', 'Credenciales incorrectas');
            $this->redirect('login');
            return;
        }

        // Establecer sesión
        Session::set('user_id', $user['id']);
        Session::set('user_name', $user['nombre']);
        Session::set('user_email', $user['email']);
        Session::set('user_role', $user['rol']); // Asegurar que el rol se guarde correctamente

        Session::setFlash('success', '¡Bienvenido ' . $user['nombre'] . '!');

        // Redirigir según el rol
        if ($user['rol'] === 'admin') {
            $this->redirect('admin');
        } else {
            $this->redirect('dashboard');
        }
    }

//Mostramos el formulario de registro
    public function showRegister() {
        // Si ya está autenticado, redirigir
        if (Session::isAuthenticated()) {
            $this->redirect('');
            return;
        }

        $this->view('auth/register', [
            'title' => 'Registrarse'
        ]);
    }

    public function register() {
        if (!$this->isPost()) {
            $this->redirect('register');
            return;
        }

        $nombre = $this->post('nombre');
        $email = $this->post('email');
        $password = $this->post('password');
        $password_confirm = $this->post('password_confirm');

        // Validar datos
        $validator = new Validator();
        $validator->required('nombre', $nombre, 'El nombre es requerido')
                  ->min('nombre', $nombre, 3, 'El nombre debe tener al menos 3 caracteres')
                  ->required('email', $email, 'El email es requerido')
                  ->email('email', $email, 'Email inválido')
                  ->required('password', $password, 'La contraseña es requerida')
                  ->min('password', $password, 6, 'La contraseña debe tener al menos 6 caracteres')
                  ->match('password_confirm', $password_confirm, $password, 'Las contraseñas no coinciden');

        if ($validator->fails()) {
            Session::setFlash('error', $validator->getFirstError());
            Session::set('old_nombre', $nombre);
            Session::set('old_email', $email);
            $this->redirect('register');
            return;
        }

        // Verificar si el email ya existe
        if ($this->userModel->emailExists($email)) {
            Session::setFlash('error', 'Este email ya está registrado');
            Session::set('old_nombre', $nombre);
            $this->redirect('register');
            return;
        }

        // Crear usuario
        $this->userModel->nombre = $nombre;
        $this->userModel->email = $email;
        $this->userModel->password = $password;
        $this->userModel->rol = 'usuario';

        if ($this->userModel->create()) {
            // Auto-login después del registro
            Session::set('user_id', $this->userModel->id);
            Session::set('user_name', $nombre);
            Session::set('user_email', $email);
            Session::set('user_role', 'usuario');

            Session::setFlash('success', '¡Registro exitoso! Bienvenido ' . $nombre);
            $this->redirect('dashboard');
        } else {
            Session::setFlash('error', 'Error al crear la cuenta. Intenta nuevamente');
            $this->redirect('register');
        }
    }


    public function logout() {
        Session::destroy();
        Session::setFlash('success', 'Sesión cerrada correctamente');
        $this->redirect('');
    }
}