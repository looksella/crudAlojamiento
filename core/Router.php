<?php
/**
 * Clase Router
 * Responsabilidad: Gestionar el enrutamiento de la aplicación
 * Principio SOLID: Single Responsibility Principle (SRP)
 */
class Router {
    private $routes = [];
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Registrar una ruta GET
     */
    public function get($path, $controller, $method) {
        $this->routes['GET'][$path] = ['controller' => $controller, 'method' => $method];
        return $this;
    }

    /**
     * Registrar una ruta POST
     */
    public function post($path, $controller, $method) {
        $this->routes['POST'][$path] = ['controller' => $controller, 'method' => $method];
        return $this;
    }

    /**
     * Ejecutar el enrutador
     */
    public function run() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remover el directorio base si existe
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/') {
            $requestUri = str_replace($scriptName, '', $requestUri);
        }
        
        // Asegurar que comience con /
        if (empty($requestUri) || $requestUri[0] !== '/') {
            $requestUri = '/' . $requestUri;
        }

        // Buscar la ruta
        if (isset($this->routes[$requestMethod][$requestUri])) {
            $route = $this->routes[$requestMethod][$requestUri];
            $controllerName = $route['controller'];
            $methodName = $route['method'];

            // Incluir el controlador
            require_once __DIR__ . "/../controllers/{$controllerName}.php";

            // Instanciar el controlador
            $controller = new $controllerName($this->db);

            // Ejecutar el método
            $controller->$methodName();
        } else {
            // Ruta no encontrada
            http_response_code(404);
            echo "<!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>404 - Página no encontrada</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                        margin: 0;
                        background: #f5f5f5;
                    }
                    .error-container {
                        text-align: center;
                        padding: 2rem;
                        background: white;
                        border-radius: 10px;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    }
                    h1 { color: #333; font-size: 5rem; margin: 0; }
                    p { color: #666; font-size: 1.2rem; }
                    a { color: #0078D7; text-decoration: none; }
                </style>
            </head>
            <body>
                <div class='error-container'>
                    <h1>404</h1>
                    <p>Página no encontrada</p>
                    <a href='/'>Volver al inicio</a>
                </div>
            </body>
            </html>";
        }
    }
}