<?php

namespace Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Http\Message\ServerRequestInterface;

use function FastRoute\simpleDispatcher;

class Routing
{
    private static ?Routing $instance = null;

    protected Dispatcher $dispatcher;

    public static function init(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
            self::$instance->initDispatcher();
        }

        return self::$instance;
    }

    public function route(ServerRequestInterface $request)
    {
        $httpMethod = $request->getMethod();

        $uri = $request->getRequestTarget();

        if (false !== ($pos = strpos($uri, '?'))) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                echo '404 Not Found';
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                echo '405 Method Not Allowed';
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                // ... call $handler with $vars
                break;
        }
    }

    protected function initDispatcher()
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {
            $routes = new Route(
                'GET',
                '/',
                '../../App/Controller/Homepage.php'
            );
            foreach ($routes as $route) {
                $r->addRoute(
                    $route->getMethod(),
                    $route->getUrl(),
                    $route->getController()
                );
            }
        });

        $this->dispatcher = $dispatcher;
    }
}
