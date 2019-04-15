<?php

class Router
{
    private $request;
    private $supportedHttpMethods = array("GET","POST");

    public function __construct(IRequest $request)
    {
        $this->request = $request;
    }

    public function __call($name, $args)
    {
        list($route, $method) = $args;
        if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            $this->invalidMethodHandler();
        }

        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    private function invalidMethodHandler()
    {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    private function defaultRequestHandler()
    {
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    public function resolve()
    {
        $controllerAction = null;

        if (isset($this->{strtolower($this->request->requestMethod)})) {
            $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        } else {
            $this->invalidMethodHandler();
            return;
        }

        $formatedRoute = $this->formatRoute($this->request->requestUri);

        if (array_key_exists($formatedRoute, $methodDictionary)) {
            $controllerAction = $methodDictionary[$formatedRoute];
        }

        if (is_null($controllerAction)) {
            $this->defaultRequestHandler();
            return;
        }

        $controllerActionArr = explode("@", $controllerAction);
        $controller = $controllerActionArr[0];
        $method = $controllerActionArr[1];
        $controller = $this->loadController($controller);

        echo call_user_func_array([$controller, $method], array($this->request));
    }

    public function loadController($name)
    {
        $file = ROOT . 'Controllers/' . $name . '.php';
        require($file);
        $controller = new $name();
        return $controller;
    }

    /* TODO Odvoji routes u poseban fajl
     public function getRoutes()
     {
         require (ROOT . 'routes/routes.php');
     } */

    public function __destruct()
    {
        $this->resolve();
    }
}
