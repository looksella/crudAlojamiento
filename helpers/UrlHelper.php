<?php
//creamos el helper para manejar las URLs de la aplicación 
class UrlHelper {
   //obtener la URL base de la aplicación
    public static function base() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $basePath = self::basePath();
        
        return $protocol . '://' . $host . $basePath;
    }
    
    //se obtiene la ruta base
    public static function basePath() {
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        
        // Si el script está en /public/, necesitamos el directorio padre
        if (strpos($scriptName, '/public') !== false || strpos($scriptName, '\\public') !== false) {
            $scriptName = dirname($scriptName);
        }
        
        // Normalizar separadores de directorio
        $scriptName = str_replace('\\', '/', $scriptName);
        
        // Asegurar que termine con /
        if ($scriptName !== '/') {
            $scriptName = rtrim($scriptName, '/') . '/';
        }
        
        return $scriptName;
    }
    
    //generamos una URL completa
    public static function to($path = '') {
        $basePath = self::basePath();
        
       //eliminar el / inicial si existe
        $path = ltrim($path, '/');
        
        return $basePath . $path;
    }
    
   //generamos una URL para los assets
    public static function asset($path) {
        $basePath = self::basePath();
        $path = ltrim($path, '/');
        return $basePath . 'public/' . $path;
    }
}

