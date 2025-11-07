<?php
/**
 * Clase Session
 * Responsabilidad: Gestionar sesiones de usuario
 */
class Session {
    /**
     * Iniciar sesión si no está iniciada
     */
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Establecer un valor en la sesión
     */
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Obtener un valor de la sesión
     */
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Verificar si existe una clave en la sesión
     */
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Eliminar un valor de la sesión
     */
    public static function delete($key) {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Destruir toda la sesión
     */
    public static function destroy() {
        self::start();
        session_unset();
        session_destroy();
    }

    /**
     * Verificar si el usuario está autenticado
     */
    public static function isAuthenticated() {
        return self::has('user_id');
    }

    /**
     * Verificar si el usuario es administrador
     */
    public static function isAdmin() {
        return self::get('user_role') === 'admin';
    }

    /**
     * Obtener el ID del usuario actual
     */
    public static function getUserId() {
        return self::get('user_id');
    }

    /**
     * Establecer un mensaje flash
     */
    public static function setFlash($type, $message) {
        self::set('flash_' . $type, $message);
    }

    /**
     * Obtener y eliminar un mensaje flash
     */
    public static function getFlash($type) {
        $message = self::get('flash_' . $type);
        self::delete('flash_' . $type);
        return $message;
    }
}