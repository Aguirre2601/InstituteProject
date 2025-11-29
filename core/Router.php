<?php
class Router {
    public function run() {
        // 1. Obtener la URL. Si no hay nada, vamos al 'home'
        $url = isset($_GET['url']) ? $_GET['url'] : 'home';
        
        // 2. Dividir la URL en partes (Controlador / Método / Parámetros...)
        $url = rtrim($url, '/');
        $url = explode('/', $url);

        // 3. Definir el Controlador
        $controllerName = isset($url[0]) ? ucwords($url[0]) . 'Controller' : 'HomeController';
        
        $file = ROOT_PATH . 'app/controllers/' . $controllerName . '.php'; 

        if(file_exists($file)) {
            // require_once del controlador (asumiendo que el Autoloader lo incluye)
            
            $controller = new $controllerName;

            // 4. Definir el método a ejecutar
            $methodName = isset($url[1]) ? $url[1] : 'index';
            
            if(method_exists($controller, $methodName)) {
                
                // 5. CAPTURAR TODOS LOS PARÁMETROS RESTANTES
                // Extraemos todos los segmentos de la URL a partir del índice 2
                // Si la URL es: profesor/darDeBajaAlumno/12/5
                // $url[0] = profesor, $url[1] = darDeBajaAlumno
                // $paramsArray será: [12, 5]
                $paramsArray = array_slice($url, 2); 
                
                // Ejecutamos el método del controlador. El operador ... (splat) 
                // "desempaca" el array $paramsArray en argumentos individuales.
                // Es decir, llama a $controller->darDeBajaAlumno(12, 5);
                $controller->{$methodName}(...$paramsArray); 
                
            } else {
                echo "Error 404: El método '{$methodName}' no existe en el controlador '{$controllerName}'.";
            }
        } else {
            echo "Error 404: El controlador '{$controllerName}' no existe.";
        }
    }
}