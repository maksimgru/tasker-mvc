<?php

namespace App\Core;

use App\Controllers\HomeController;

class App
{
    /** @var string */
    protected $controller = HomeController::class;

    /** @var string */
    protected $method = 'indexAction';

    /** @var array */
    protected $params = [];

    /**
     * Create new App instance.
     */
    public function __construct()
    {
        // Parse URI and split it into: controller name, action name, array of params value
        $uri = Helpers::parseURI();

        // Check if exists file with passed controller name
        $controllerName = isset($uri[0]) ? ucwords($uri[0]) . 'Controller' : '';
        if (isset($uri[0]) && !empty($uri[0]) && file_exists('../app/Controllers/' . $controllerName . '.php')) {
            $this->controller = 'App\\Controllers\\' . $controllerName;
            unset($uri[0]);
        }

        // Instantiate Controller object
        $this->controller = new $this->controller;

        // Check if controller class has passed action method
        if (isset($uri[1])) {
            $actionName = $uri[1] . 'Action';
            if (method_exists($this->controller, $actionName)) {
                $this->method = $actionName;
                unset($uri[1]);
            }
        }

        // Other URI params
        if ($uri) {
            $this->params = array_values($uri);
        }

        // Execute controller action method and pass params in this controller action method.
        \call_user_func_array([$this->controller, $this->method], $this->params);
   }
}
