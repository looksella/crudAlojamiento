<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Alojamiento.php';
require_once __DIR__ . '/../models/UserAlojamiento.php';
require_once __DIR__ . '/../helpers/Session.php';

/**
 * Clase UserController
 * Responsabilidad: Gestionar operaciones de usuario
 * Principio SOLID: Single Responsibility Principle (SRP)
 */
class UserController extends Controller {
    private $db;
    private $userModel;
    private $alojamientoModel;
    private $userAlojamientoModel;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->alojamientoModel = new Alojamiento($db);
        $this->userAlojamientoModel = new UserAlojamiento($db);
    }

    /**
     * Dashboard del usuario
     */
    public function dashboard() {
        $this->requireAuth();

        $userId = Session::getUserId();

        // Obtener alojamientos seleccionados
        $misAlojamientos = $this->userAlojamientoModel->getUserAlojamientos($userId);

        // Obtener información del usuario
        $user = $this->userModel->findById($userId);

        $this->view('user/dashboard', [
            'title' => 'Mi Cuenta',
            'user' => $user,
            'alojamientos' => $misAlojamientos,
            'totalSelecciones' => count($misAlojamientos)
        ]);
    }

    /**
     * Panel de administrador
     */
    public function adminPanel() {
        $this->requireAdmin();

        // Obtener todos los alojamientos
        $alojamientos = $this->alojamientoModel->getAll(null);

        // Obtener estadísticas
        $totalAlojamientos = $this->alojamientoModel->count(null);
        $totalUsuarios = $this->userModel->count();
        $totalSelecciones = $this->userAlojamientoModel->getTotalSelections();

        $this->view('admin/panel', [
            'title' => 'Panel de Administrador',
            'alojamientos' => $alojamientos,
            'totalAlojamientos' => $totalAlojamientos,
            'totalUsuarios' => $totalUsuarios,
            'totalSelecciones' => $totalSelecciones
        ]);
    }
}