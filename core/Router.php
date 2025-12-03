<?php 
// core/Router.php
class Router {
    protected $routes = [];

    public function add($url, $controller, $method, $middleware = null, $middlewareParam = null) {
        // Normalizar la URL - siempre sin slash al inicio
        $url = ltrim($url, '/');
        $this->routes[$url] = [
            'controller' => $controller,
            'method' => $method,
            'middleware' => $middleware,
            'param' => $middlewareParam,
        ];
    }
    
    public function registerRoutes() {
        // === RUTAS PÚBLICAS (sin middleware) ===
        $this->add('', 'HomeController', 'index');
        $this->add('home/login', 'HomeController', 'login');
        $this->add('auth/iniciar', 'AuthController', 'iniciar');
        $this->add('auth/logout', 'AuthController', 'logout');
        $this->add('auth/crearUsuario', 'AuthController', 'crearUsuario');
        $this->add('alumno/vistaCrearUsuarioAlumno', 'AlumnoController', 'vistaCrearUsuarioAlumno');
        $this->add('alumno/crearUsuarioAlumno', 'AlumnoController', 'crearUsuarioAlumno');
        $this->add('auth/vistaRecuperaContrasenia', 'AuthController', 'vistaRecuperaContrasenia');
        $this->add('auth/recuperaContrasenia', 'AuthController', 'recuperaContrasenia');

        // === RUTAS PROTEGIDAS POR ROL ===
        
        // Director
        $this->add('director/dashboard', 'DirectorController', 'dashboard', 'CheckRoleMiddleware', 'D');
        $this->add('director/darDeBajaProfesor/:id', 'DirectorController', 'darDeBajaProfesor', 'CheckRoleMiddleware', 'D');
        $this->add('director/vistaCrearProfesor', 'DirectorController', 'vistaCrearProfesor', 'CheckRoleMiddleware', 'D');
        $this->add('director/crearProfesor', 'DirectorController', 'crearProfesor', 'CheckRoleMiddleware', 'D');
        $this->add('director/vistaEditarPerfil', 'DirectorController', 'vistaEditarPerfil', 'CheckRoleMiddleware', 'D');
        $this->add('director/actualizarPerfil', 'DirectorController', 'actualizarPerfil', 'CheckRoleMiddleware', 'D');
        $this->add('director/enviarEmailProfesor/:destinatario/:apellido/:password/:usuario_name', 'DirectorController', 'enviarEmailProfesor', 'CheckRoleMiddleware', 'D');

        

        // Profesor
        $this->add('profesor/dashboard', 'ProfesorController', 'dashboard', 'CheckRoleMiddleware', 'P');
        $this->add('profesor/vistaEditarPerfil', 'ProfesorController', 'vistaEditarPerfil', 'CheckRoleMiddleware', 'P');
        $this->add('profesor/actualizarPerfil', 'ProfesorController', 'actualizarPerfil', 'CheckRoleMiddleware', 'P');
        $this->add('profesor/darDeBajaAlumno/:id/:carrera', 'ProfesorController', 'darDeBajaAlumno', 'CheckRoleMiddleware', 'P');
        
        // Alumno
        $this->add('alumno/vistaEditarPerfil', 'AlumnoController', 'vistaEditarPerfil', 'CheckRoleMiddleware', 'A');
        $this->add('alumno/actualizarPerfil', 'AlumnoController', 'actualizarPerfil', 'CheckRoleMiddleware', 'A');
    
    }

    public function run() {
        // Registrar todas las rutas
        $this->registerRoutes();
        
        // Obtener la URL solicitada
        $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
        $url = ltrim($url, '/'); // Asegurar que no tenga slash al inicio
        
        //echo "DEBUG - URL solicitada: '$url'<br>"; // TEMPORAL para debugging
        
        // Buscar coincidencia exacta primero
        if (isset($this->routes[$url])) {
            $this->dispatch($this->routes[$url]);
            return;
        }
        
        // Buscar coincidencia con parámetros
        foreach ($this->routes as $routeUrl => $routeConfig) {
            if (strpos($routeUrl, ':') !== false && $this->matchPattern($routeUrl, $url, $params)) {
                $this->dispatch($routeConfig, $params);
                return;
            }
        }
        
        // Ruta no encontrada
        //http_response_code(404);
        //echo "Ruta no encontrada: " . $url;
        $accesoDenegadoMiddleware = new CheckRoleMiddleware();
        $accesoDenegadoMiddleware->accesoDenegado();
    }
    
    private function matchPattern($routeUrl, $requestUrl, &$params) {
        $routeParts = explode('/', $routeUrl);
        $requestParts = explode('/', $requestUrl);
        
        if (count($routeParts) !== count($requestParts)) {
            return false;
        }
        
        $params = [];
        foreach ($routeParts as $index => $routePart) {
            if (strpos($routePart, ':') === 0) {
                $paramName = substr($routePart, 1);
                $params[$paramName] = $requestParts[$index];
            } elseif ($routePart !== $requestParts[$index]) {
                return false;
            }
        }
        
        return true;
    }
    
    private function dispatch($route, $params = []) {
        // --- EJECUCIÓN DEL MIDDLEWARE ---
        if ($route['middleware']) {
            $middlewareClass = $route['middleware'];
            $middlewareParam = $route['param'];

            $middleware = new $middlewareClass();
            if (!$middleware->handle($middlewareParam)) {
                // El middleware ya manejó la respuesta (acceso denegado)
                return;
            }
        }
        
        // --- LLAMADA AL CONTROLADOR ---
        $controllerName = $route['controller'];
        $methodName = $route['method'];
        
        $controller = new $controllerName(); 
        
        // Pasar parámetros si existen
        if (!empty($params)) {
            $controller->{$methodName}(...array_values($params));
        } else {
            $controller->{$methodName}();
        }
    }
}
?>