<?php

class Routing
{
    /** @var array uriExploded */
    private $uriExploded;

    /** @var string controllerArea */
    private $controllerArea;
    /** @var string controllerName */
    private $controllerName;
    /** @var string actionName */
    private $actionName;

    /** @var array params */
    private $params;

    public function __construct()
    {
        $uri = $_SERVER["REQUEST_URI"];
        $uri = preg_replace("#" . BASE_PATH_PATTERN . "#i", "", $uri, 1);
        $uri = trim($uri, "/");

        $this->uriExploded = explode("/", $uri);

        if ($this->checkAdmin()) {
            $this->handleAdmin();
        } else {
            $this->handleFront();
        }

        Helpers::debug($this->uriExploded);

        $this->setController();
        $this->setAction();
        $this->setParams();

        $this->runRoute();
    }

    /**
     * @return bool
     */
    public function checkAdmin()
    {
        if (!isset($this->uriExploded[0])) {
            return false;
        }

        return ($this->uriExploded[0] === ADMIN_PATH);
    }

    public function handleAdmin()
    {
        // TODO : check if connected

        $this->controllerArea = 'admin';

        unset($this->uriExploded[0]);
        $this->uriExploded = array_values($this->uriExploded);
    }

    public function handleFront()
    {
        $this->controllerArea = 'front';
    }

    public function setController()
    {
        if (isset($this->uriExploded[0])) {
            $requested = $this->uriExploded[0];
        }

        $controller = (empty($requested)) ? "Index" : ucfirst($requested);
        $this->controllerName = $controller . "Controller" . ucfirst($this->controllerArea);

        unset($this->uriExploded[0]);
    }

    public function setAction()
    {
        if (isset($this->uriExploded[1])) {
            $requested = $this->uriExploded[1];
        }

        $action = (empty($requested)) ? "index" : $requested;
        $this->actionName = $action . "Action";

        unset($this->uriExploded[1]);
    }

    public function setParams()
    {
        $this->params = array_merge(array_values($this->uriExploded), $_POST);
    }

    /**
     * @return bool
     */
    public function checkRoute()
    {
        $controllerPath = "controllers/" . $this->controllerArea . '/' . $this->controllerName . ".class.php";
        if (!file_exists($controllerPath)) {
            return false;
        }
        include $controllerPath;

        if (!class_exists($this->controllerName)) {
            return false;
        }

        if (!method_exists($this->controllerName, $this->actionName)) {
            return false;
        }

        return true;
    }

    public function runRoute()
    {
        if ($this->checkRoute()) {
            $controller = new $this->controllerName();
            $controller->{$this->actionName}($this->params);
        } else {
            $this->page404();
        }
    }

    public function page404()
    {
        die("Error 404");
        // TODO
    }

}
