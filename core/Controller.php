<?php

abstract class Controller {
    //Aquí se renderiza la vista
    protected function view($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . "/../views/{$view}.php";
        
        if (!file_exists($viewPath)) {
            die("Vista no encontrada: {$view}");
        }
        
        require_once $viewPath;
    }

    //redirigir a una URL
    protected function redirect($url) {
        require_once __DIR__ . "/../helpers/UrlHelper.php";
        $fullUrl = UrlHelper::to($url);
        header("Location: {$fullUrl}");
        exit;
    }

    //Aquí se retorna el JSON
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    //Se verifica si es una petición POST
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

   //Se verifica si es una petición GET
    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    //Obtener datos POST
    protected function post($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    //Obtener datos GET
    protected function get($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    //se verifica que este autenticado
    protected function requireAuth() {
        if (!Session::isAuthenticated()) {
            Session::setFlash('error', 'Debes iniciar sesión para poder acceder');
            $this->redirect('login');
        }
    }

    //verficamos que sea administrado
    protected function requireAdmin() {
        $this->requireAuth();
        if (!Session::isAdmin()) {
            Session::setFlash('error', 'No tienes permisos para acceder >:v');
            $this->redirect('/');
        }
    }
}