<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Alojamiento.php';
require_once __DIR__ . '/../models/UserAlojamiento.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';

class AlojamientoController extends Controller {
    private $db;
    private $alojamientoModel;
    private $userAlojamientoModel;

    public function __construct($db) {
        $this->db = $db;
        $this->alojamientoModel = new Alojamiento($db);
        $this->userAlojamientoModel = new UserAlojamiento($db);
    }


    public function index() {
        $alojamientos = $this->alojamientoModel->getAll(true);
        
        // Si el usuario está autenticado, obtener sus selecciones
        $selectedIds = [];
        if (Session::isAuthenticated()) {
            $selectedIds = $this->userAlojamientoModel->getUserAlojamientoIds(
                Session::getUserId()
            );
        }

        $this->view('home/index', [
            'title' => 'Alojamientos Disponibles',
            'alojamientos' => $alojamientos,
            'selectedIds' => $selectedIds
        ]);
    }

   //ahora creamos un nuevo alojamiento
    public function create() {
        $this->requireAdmin();

        if (!$this->isPost()) {
            $this->redirect('/admin');
            return;
        }

        $nombre = $this->post('nombre');
        $descripcion = $this->post('descripcion');
        $ubicacion = $this->post('ubicacion');
        $precio = $this->post('precio');
        $capacidad = $this->post('capacidad');
        $habitaciones = $this->post('habitaciones');
        $banos = $this->post('banos');
        $imagen = $this->post('imagen');
        $wifi = $this->post('wifi', false);
        $estacionamiento = $this->post('estacionamiento', false);
        $piscina = $this->post('piscina', false);

        // Validar datos
        $validator = new Validator();
        $validator->required('nombre', $nombre, 'El nombre es requerido')
                  ->max('nombre', $nombre, 100)
                  ->required('descripcion', $descripcion, 'La descripción es requerida')
                  ->required('ubicacion', $ubicacion, 'La ubicación es requerida')
                  ->max('ubicacion', $ubicacion, 100)
                  ->required('precio', $precio, 'El precio es requerido')
                  ->numeric('precio', $precio, 'El precio debe ser un número')
                  ->required('capacidad', $capacidad, 'La capacidad es requerida')
                  ->numeric('capacidad', $capacidad)
                  ->required('habitaciones', $habitaciones, 'Las habitaciones son requeridas')
                  ->numeric('habitaciones', $habitaciones)
                  ->required('banos', $banos, 'Los baños son requeridos')
                  ->numeric('banos', $banos);

        if ($validator->fails()) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/admin');
            return;
        }

        // Crear alojamiento
        $this->alojamientoModel->nombre = $nombre;
        $this->alojamientoModel->descripcion = $descripcion;
        $this->alojamientoModel->ubicacion = $ubicacion;
        $this->alojamientoModel->precio = $precio;
        $this->alojamientoModel->capacidad = $capacidad;
        $this->alojamientoModel->habitaciones = $habitaciones;
        $this->alojamientoModel->banos = $banos;
        $this->alojamientoModel->imagen = $imagen;
        $this->alojamientoModel->wifi = $wifi ? 1 : 0;
        $this->alojamientoModel->estacionamiento = $estacionamiento ? 1 : 0;
        $this->alojamientoModel->piscina = $piscina ? 1 : 0;

        if ($this->alojamientoModel->create()) {
            Session::setFlash('success', 'Alojamiento agregado exitosamente');
        } else {
            Session::setFlash('error', 'Error al agregar el alojamiento');
        }

        $this->redirect('/admin');
    }

    //se selecciona un alojamiento
    public function select() {
        $this->requireAuth();

        if (!$this->isPost()) {
            $this->redirect('/');
            return;
        }

        $alojamientoId = $this->post('alojamiento_id');

        if (empty($alojamientoId)) {
            Session::setFlash('error', 'Alojamiento no válido');
            $this->redirect('/');
            return;
        }

        // Verificar que el alojamiento existe
        $alojamiento = $this->alojamientoModel->findById($alojamientoId);
        if (!$alojamiento) {
            Session::setFlash('error', 'Alojamiento no encontrado');
            $this->redirect('/');
            return;
        }

        // Agregar selección
        $this->userAlojamientoModel->id_usuario = Session::getUserId();
        $this->userAlojamientoModel->id_alojamiento = $alojamientoId;

        if ($this->userAlojamientoModel->add()) {
            Session::setFlash('success', 'Alojamiento agregado a tus selecciones');
        } else {
            Session::setFlash('error', 'Este alojamiento ya está en tus selecciones');
        }

        $this->redirect('/');
    }

    //eliminamos la selección
    public function removeSelection() {
        $this->requireAuth();

        if (!$this->isPost()) {
            $this->redirect('/dashboard');
            return;
        }

        $alojamientoId = $this->post('alojamiento_id');

        if (empty($alojamientoId)) {
            Session::setFlash('error', 'Alojamiento no válido');
            $this->redirect('/dashboard');
            return;
        }

        // Eliminar selección
        $this->userAlojamientoModel->id_usuario = Session::getUserId();
        $this->userAlojamientoModel->id_alojamiento = $alojamientoId;

        if ($this->userAlojamientoModel->remove()) {
            Session::setFlash('success', 'Alojamiento eliminado de tus selecciones');
        } else {
            Session::setFlash('error', 'Error al eliminar el alojamiento');
        }

        $this->redirect('/dashboard');
    }

    //buscamos los alojamientos
    public function search() {
        $search = $this->get('q', '');
        $ubicacion = $this->get('ubicacion', '');
        $precioMin = $this->get('precio_min');
        $precioMax = $this->get('precio_max');

        $alojamientos = $this->alojamientoModel->search(
            $search, 
            $ubicacion, 
            $precioMin, 
            $precioMax
        );

        // Si el usuario está autenticado, obtener sus selecciones
        $selectedIds = [];
        if (Session::isAuthenticated()) {
            $selectedIds = $this->userAlojamientoModel->getUserAlojamientoIds(
                Session::getUserId()
            );
        }

        $this->view('home/index', [
            'title' => 'Resultados de Búsqueda',
            'alojamientos' => $alojamientos,
            'selectedIds' => $selectedIds,
            'search' => $search,
            'ubicacion' => $ubicacion
        ]);
    }
}