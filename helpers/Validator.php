<?php

class Validator {
    private $errors = [];

    //se verifica que el campo no esté vacío
    public function required($field, $value, $message = null) {
        if (empty(trim($value))) {
            $this->errors[$field] = $message ?? "El campo {$field} es requerido";
        }
        return $this;
    }

    //se verifica que el email sea válido
    public function email($field, $value, $message = null) {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?? "El email no es válido";
        }
        return $this;
    }

    //validamos la longitud mínima
    public function min($field, $value, $min, $message = null) {
        if (!empty($value) && strlen($value) < $min) {
            $this->errors[$field] = $message ?? "El campo {$field} debe tener al menos {$min} caracteres";
        }
        return $this;
    }

    //se verifica que la longitud máxima sea correcta
    public function max($field, $value, $max, $message = null) {
        if (!empty($value) && strlen($value) > $max) {
            $this->errors[$field] = $message ?? "El campo {$field} debe tener máximo {$max} caracteres";
        }
        return $this;
    }

    //se verifica que sea un número
    public function numeric($field, $value, $message = null) {
        if (!empty($value) && !is_numeric($value)) {
            $this->errors[$field] = $message ?? "El campo {$field} debe ser un número";
        }
        return $this;
    }

    //ahora se verifica que los campos coincidan
    public function match($field, $value, $matchValue, $message = null) {
        if ($value !== $matchValue) {
            $this->errors[$field] = $message ?? "Los campos no coinciden";
        }
        return $this;
    }

   //entonces se verifica si hay errores
    public function fails() {
        return !empty($this->errors);
    }

    //además se verifica si la validación pasó
    public function passes() {
        return empty($this->errors);
    }

    //se obtienen todos los errores
    public function getErrors() {
        return $this->errors;
    }

    //se obtiene el primer error
    public function getFirstError() {
        return !empty($this->errors) ? reset($this->errors) : null;
    }

    //necesariamente se limpia el HTML
    public static function clean($data) {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
}