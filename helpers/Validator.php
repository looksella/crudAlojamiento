<?php
/**
 * Clase Validator
 * Responsabilidad: Validar datos de entrada
 * Principio SOLID: Single Responsibility Principle (SRP)
 */
class Validator {
    private $errors = [];

    /**
     * Validar que un campo no esté vacío
     */
    public function required($field, $value, $message = null) {
        if (empty(trim($value))) {
            $this->errors[$field] = $message ?? "El campo {$field} es requerido";
        }
        return $this;
    }

    /**
     * Validar email
     */
    public function email($field, $value, $message = null) {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?? "El email no es válido";
        }
        return $this;
    }

    /**
     * Validar longitud mínima
     */
    public function min($field, $value, $min, $message = null) {
        if (!empty($value) && strlen($value) < $min) {
            $this->errors[$field] = $message ?? "El campo {$field} debe tener al menos {$min} caracteres";
        }
        return $this;
    }

    /**
     * Validar longitud máxima
     */
    public function max($field, $value, $max, $message = null) {
        if (!empty($value) && strlen($value) > $max) {
            $this->errors[$field] = $message ?? "El campo {$field} debe tener máximo {$max} caracteres";
        }
        return $this;
    }

    /**
     * Validar que sea un número
     */
    public function numeric($field, $value, $message = null) {
        if (!empty($value) && !is_numeric($value)) {
            $this->errors[$field] = $message ?? "El campo {$field} debe ser un número";
        }
        return $this;
    }

    /**
     * Validar que dos campos coincidan
     */
    public function match($field, $value, $matchValue, $message = null) {
        if ($value !== $matchValue) {
            $this->errors[$field] = $message ?? "Los campos no coinciden";
        }
        return $this;
    }

    /**
     * Verificar si hay errores
     */
    public function fails() {
        return !empty($this->errors);
    }

    /**
     * Verificar si la validación pasó
     */
    public function passes() {
        return empty($this->errors);
    }

    /**
     * Obtener todos los errores
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Obtener el primer error
     */
    public function getFirstError() {
        return !empty($this->errors) ? reset($this->errors) : null;
    }

    /**
     * Limpiar HTML de un string
     */
    public static function clean($data) {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}