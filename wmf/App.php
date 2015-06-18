<?php

class App
{

    private static $obj = null;
    public $log = '';


    public static function obj()
    {
        if (self::$obj === null) {
            self::$obj = new App();
        }
        return self::$obj;
    }

    private function __clone() {}
    private function __wake() {}
    private function __construct()
    {
        $this->readConfig();
    }

    private $config = null;
    private $services = [];


    private function readConfig()
    {
        $this->config = require(BASE_DIR . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'main.php');
    }

    public function getConfig($section = false)
    {
        if ($section === false) {
            return $this->config;
        }
        if (isset($this->config[$section])) {
            return $this->config[$section];
        }
        return [];
    }

    public function run()
    {
        $this->loadServices();
        $path = $this->getQueryPath();
        $routes = $this->config['routes'];

        $route = isset($routes[$path])
            ? $routes[$path]
            : $routes[$this->config['defaultRoute']];

        echo $this->runController($route);

    }

    public function runController($route)
    {
        $routeParts = explode('::', $route);
        $controller = '\\Controllers\\' . $routeParts[0] . 'Controller';
        $action = $routeParts[1];
        ob_start();
            $result = (new $controller)->$action();
            $this->log = ob_get_contents();
        ob_clean();
        return $result;
    }

    private function getQueryPath()
    {
        $path = explode('?', $_SERVER['REQUEST_URI'])[0];
        $path = rtrim($path, '/');
        return ($path);
    }

    public function __get($name) {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }
        return null;
    }

    public function registerService($name, $instance) {
        $this->services[$name] = $instance;
    }

    protected function loadServices() {
        foreach ($this->getConfig('services') as $name => $class) {
            $this->registerService($name, new $class);
        }
    }

}