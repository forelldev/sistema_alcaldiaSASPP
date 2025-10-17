<?php
// core/Router.php
class Router
{
    private static $routes = [];

    public static function get($uri, $controllerAction)
    {
        self::$routes['GET'][$uri] = $controllerAction;
    }

    public static function post($uri, $controllerAction)
    {
        self::$routes['POST'][$uri] = $controllerAction;
    }

public static function dispatch()
{
    // URI solicitada (sin parámetros)
    $uri = strtok($_SERVER['REQUEST_URI'], '?');

    // Carpeta base del proyecto (por ejemplo: /Entender PHP PDO)
    $basePath = dirname($_SERVER['SCRIPT_NAME']);

    // Elimina la carpeta base de la URI
    if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
        $uri = substr($uri, strlen($basePath));
    }

    // Normaliza la URI
    $uri = '/' . trim($uri, '/');

    // Si queda vacío, es la raíz
    if ($uri === '/')
        $uri = '/';

    $method = $_SERVER['REQUEST_METHOD'];

    // Verifica si la ruta existe
    if (isset(self::$routes[$method][$uri])) {
        $controllerAction = self::$routes[$method][$uri];
        list($controllerName, $actionName) = explode('@', $controllerAction);

        $controllerPath = 'controlador/' . $controllerName . '.php';

        if (file_exists($controllerPath)) {
            require_once $controllerPath;

            if (class_exists($controllerName)) {
                $controller = new $controllerName();

                if (method_exists($controller, $actionName)) {
                    $controller->$actionName();
                    return;
                } else {
                    echo "Error: El método <strong>$actionName</strong> no existe en <strong>$controllerName</strong>.";
                    return;
                }
            } else {
                echo "Error: La clase <strong>$controllerName</strong> no fue encontrada.";
                return;
            }
        } else {
            echo "Error: El archivo <strong>$controllerPath</strong> no existe.";
            return;
        }
    }

    // Ruta no encontrada
    echo "<h2>Error 404: Página no encontrada</h2>";
    echo "Método: <strong>$method</strong><br>";
    echo "URI solicitada: <strong>$uri</strong><br>";
}

}
?>