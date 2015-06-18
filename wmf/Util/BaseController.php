<?php

namespace Util;

/**
 * Class BaseController
 * @package Util
 * @method init
 */
abstract class BaseController {

    public $lang = 'ru';

    public $layout = 'layout';

    public $params = [];

    public function __construct() {
        if (method_exists($this, 'init')) {
            $this->init();
        }
    }

    protected function render ($view, $parameters = []) {
        return $this->renderPartial($this->layout, [
            'content' => $this->renderPartial($view, $parameters),
            'params' => $this->params
        ]);
    }

    protected function renderPartial ($view, $parameters)
    {
        $viewPath = BASE_DIR . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $view . '.php';
        foreach ($parameters as $varName => $varValue) {
            $$varName = $varValue;
        }
        ob_start();
            include($viewPath);
            $result = ob_get_contents();
            ob_end_clean();
        return $result;
    }

    public function forward($location) {
        $config = \App::obj()->getConfig('routes');
        $route = $config[$location];
        return \App::obj()->runController($route);
    }

    public function redirect($location) {
        header('Location: ' . $location);
        exit();
    }

}