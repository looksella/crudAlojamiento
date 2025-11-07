<?php
/**
 * Clase Controller (Base)
 * Responsabilidad: Proporcionar funcionalidad común a todos los controladores
 * Principio SOLID: Open/Closed Principle (OCP) - Abierto para extensión, cerrado para modificación
 */
abstract class Controller {
    /**
     * Renderizar una vista
     */
    protected function view($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . "/../views/{$view}.php";
        
        if (!file_exists($viewPath)) {
            die("Vista no encontrada: {$view}");
        }
        
        require_once $viewPath;
    }

    /**
     * Redirigir a una URL
     */
    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }

    /**
     * Retornar JSON
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Verificar si es una petición POST
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Verificar si es una petición GET
     */
    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Obtener datos POST
     */
    protected function post($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    /**
     * Obtener datos GET
     */
    protected function get($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    /**
     * Verificar autenticación
     */
    protected function requireAuth() {
        if (!Session::isAuthenticated()) {
            Session::setFlash('error', 'Debes iniciar sesión para acceder');
            $this->redirect('/login');
        }
    }

    /**
     * Verificar que sea administrador
     */
    protected function requireAdmin() {
        $this->requireAuth();
        if (!Session::isAdmin()) {
            Session::setFlash('error', 'No tienes permisos para acceder');
            $this->redirect('/');
        }
    }
}