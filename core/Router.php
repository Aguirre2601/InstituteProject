<?php
class Router {
    public function run() {
        // 1. Obtener la URL. Si no hay nada, vamos al 'home'
        // El .htaccess (si lo tienes) debe pasar la URL a $_GET['url']
        $url = isset($_GET['url']) ? $_GET['url'] : 'home';
        
        // 2. Dividir la URL en partes (Controlador / Método / Parámetro)
        $url = rtrim($url, '/');
        $url = explode('/', $url);

        // 3. Definir el Controlador (ej: UsuarioController, DirectorController)
        $controllerName = isset($url[0]) ? ucwords($url[0]) . 'Controller' : 'HomeController';
        
        // El archivo se busca usando la constante ROOT_PATH definida en index.php
        $file = ROOT_PATH . 'app/controllers/' . $controllerName . '.php'; 

        if(file_exists($file)) {
            // El Autoloader en index.php se encargará de incluirlo
            // require_once $file; 
            
            $controller = new $controllerName;

            // 4. Definir el método a ejecutar (ej: dashboard, listar, crear)
            $methodName = isset($url[1]) ? $url[1] : 'index';
            
            if(method_exists($controller, $methodName)) {
                // 5. Capturar el parámetro (ej: el ID 5 en /usuario/editar/5)
                $param = isset($url[2]) ? $url[2] : null;
                
                // Ejecutamos el método del controlador, pasándole el parámetro
                $controller->{$methodName}($param); 
            } else {
                echo "Error 404: El método '{$methodName}' no existe en el controlador '{$controllerName}'.";
            }
        } else {
            echo "Error 404: El controlador '{$controllerName}' no existe.";
        }
    }
}