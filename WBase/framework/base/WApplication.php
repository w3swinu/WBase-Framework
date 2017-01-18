<?php

class WApplication extends WComponent {

    public $customclasses = array();

    public function __construct($config = array()) {
        WB::setApplication($this);
        $this->path = $config;
    }

    public function RunApplication() {
        $this->ImportConfig();
        $this->ImportCustomClasses();
        $this->SetDatabase();
        $this->ThemeURL();
        $this->RunURL();
    }

    private function ImportConfig() {
        if (!file_exists($this->path)) {
            return FALSE;
        }
        $this->config = require_once $this->path;
        unset($this->path);
    }

    private function ImportAction($files = array(), $path = '') {
        foreach ($files as $pattern) {
            foreach (glob($path . $pattern) as $filename) {
                if (file_exists($filename)) {
                    $classname = basename($filename, ".php");
                    $this->customclasses[strtolower($classname)] = $filename;
                }
            }
        }
    }

    private function ImportCustomClasses() {
        if (isset($this->config['import']) && is_array($this->config['import'])) {
            $root = WB_APPLICATION . '/';
            $this->ImportAction($this->config['import'], $root);
        }
    }

    private function SetDatabase() {
        if (isset($this->config['db']) && is_array($this->config['db'])) {
            if (isset($this->config['db']['server']) && isset($this->config['db']['dbname']) && isset($this->config['db']['username']) && isset($this->config['db']['password'])) {
                $this->db = mysqli_connect($this->config['db']['server'], $this->config['db']['username'], $this->config['db']['password'], $this->config['db']['dbname']);
                if (mysqli_connect_errno()) {
                    die('Not connected : ' . mysql_error());
                }
            }
        }
    }

    private function RunURL() {
        
        //  Run index.php?w=controller/action   or
        //  Run default controller & action
        $path = filter_input(INPUT_GET, 'w', FILTER_DEFAULT);

        if (isset($path) && !empty($path)) {
            $path = isset($this->config['URLmanager']['routes'][$path]) ? $this->config['URLmanager']['routes'][$path] : $path;
            $this->RunControllerAction($path);
        } elseif (isset($this->config['default'])) {
            $this->RunControllerAction($this->config['default']);
        }
    }

    private function RunControllerAction($contr_actn) {

        $path = explode('/', $contr_actn);
        $action = (isset($path[1]) && (!empty($path[1]))) ? $path[1] : 'index';
        if (class_exists($path[0])) {
            $this->TriggerController($path[0], $action);
        } else if (isset($this->config['error'])) {
            $path = explode('/', $this->config['error']);
            $this->TriggerController($path[0], $path[1]);
        } else {
            throw new WException('Class "' . $path[0] . '" is not defined.');
        }
    }

    private function TriggerController($controller, $action) {

        $this->controller = $controller;
        $controller_obj = new $controller;
        if (method_exists($controller_obj, $action)) {
            $this->action = $action;
            $controller_obj->$action();
            if (method_exists($controller_obj, 'init')) {
                $controller_obj->init();
            } else {
                throw new WException('Function "init()" is not defined in "' . $controller . '" class');
            }
        } else {
            throw new WException('Function "' . $action . '" is not defined in "' . $controller . '" class');
        }
    }

    public function BaseURL() {
        if (isset($_SERVER['HTTP_HOST'])) {
            $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $base_url .= '://' . $_SERVER['HTTP_HOST'];
            $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        }
        return $this->rooturl = $base_url;
    }

    private function ThemeURL() {
        if (isset($this->config['theme']) && !empty($this->config['theme'])) {
            $this->themeurl = $this->BaseURL() . 'themes/' . $this->config['theme'] . '/';
        } else {
            throw new WException('Invalid theme name is given.');
        }
    }

}
