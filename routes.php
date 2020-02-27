<?php
class Routes
{
    public $uri;
    public static function init($r)
    {
        $r->addGroup('/admin', function (FastRoute\RouteCollector $r) {
            $r->addRoute('GET', 's', '["Admin", "getAll"]');
            $r->addRoute('GET', '/{id:\d+}', '["Admin", "getById"]');

            $r->addRoute('POST', '/registrar', '["Admin", "registrar"]');
            $r->addRoute('POST', '/{id:\d+}/atualizar', '["Admin", "atualizar"]');

            $r->addRoute('POST', '/login', '["Admin", "login"]');
            $r->addRoute('GET', '/logout', '["Admin", "logout"]');
            


            $r->addRoute('DELETE', '/{id:\d+}/delete', '["Admin", "deletar"]');



        });
        // $r->addRoute('GET', '/admins', '["Admin", "get_all_admins"]');
        // $r->addRoute('GET', '/admin/{id:\d+}', '["Admin", "get_admin"]');
        //$r->addRoute('POST', '/admin/add', '["Admin", "add_admin"]');
    }

    public static function getParam()
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        $letrafinal = substr($uri, -1);
        if ($letrafinal == "/") {
            $uri = substr($uri, 0, -1);
        }
        $uri = rawurldecode($uri);
        $GLOBALS['httpMethod'] = $httpMethod;
        return [$httpMethod, $uri];
    }
    public static function startRoutes($dispatcher, $parm)
    {
        require_once 'models/Returns.php';
        $routeInfo = $dispatcher->dispatch($parm[0], $parm[1]);

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                Returns::simpleMsgError("Rota não encontrada");
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                Returns::simpleMsgError("Metodo não permitido.");
                break;
            case FastRoute\Dispatcher::FOUND:
                self::castHandler($routeInfo);
                break;
        }
    }
    private static function castHandler($route)
    {
        $httpM = "_" . $GLOBALS['httpMethod'];
        $handler = json_decode($route[1]);
        
        require_once './components/' . $handler[0] . '.php';
        $component = new $handler[0]();
        $fun = $handler[1];
        $component->$fun($httpM, $route);
    }
}

?>
