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
        $this->URLmanager();
    }

    private function ImportConfig() {
        if (!file_exists($this->path)) {
            return FALSE;
        }
        $this->config = require_once $this->path;
    }

    private function ImportAction($files = array(), $path = '') {
        foreach ($files as $pattern) {
            foreach (glob($path . $pattern) as $filename) {
                if (file_exists($filename)) {
                    $classname = basename($filename, ".php");
                    $this->customclasses[strtolower($classname)] = $filename;
                    WB::registerAutoloader(array('WCore', 'autoload'));
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
                $host = explode('=', $this->config['db']['server']);
                $con = mysqli_connect($host[1], $this->config['db']['username'], $this->config['db']['password'], $this->config['db']['dbname']);
                if (mysqli_connect_errno()) {
                    die('Not connected : ' . mysql_error());
                }
                $this->attach('db', $con);
            }
        }
    }

    private function LoadAction($contr_actn) {
        $action = '';
        $path = array();
        if (isset($contr_actn)) {
            $path = explode('/', $contr_actn);
            $action = (isset($path[1]) && (!empty($path[1]))) ? $path[1] : 'index';
            if (class_exists($path[0])) {
                $controller = new $path[0]();
                if (method_exists($controller, $action)) {
                    $controller->$action();
                    $controller->init();
                } else {
                    throw new WException('Function "' . $action . '" is not defined in the class "' . $path[0] . '".');
                }
            } else {
                throw new WException('Class "' . $path[0] . '" is not defined.');
            }
        }
    }

    private function URLmanager() {
        $path = filter_input(INPUT_GET, 'w', FILTER_DEFAULT);
        if (isset($path) && !empty($path)) {
            self::LoadAction($path);
        } elseif (isset($this->config['default'])) {
            self::LoadAction($this->config['default']);
        }
    }

    public function baseURL() {
        if (isset($_SERVER['HTTP_HOST'])) {
            $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $base_url .= '://' . $_SERVER['HTTP_HOST'];
            $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        }
        return $base_url;
    }

    public function ThemeURL() {
        if (isset($this->config['theme']) && !empty($this->config['theme'])) {
            $base_url = $this->baseURL();
            return $base_url . 'themes/' . $this->config['theme'];
        }
    }

    public function LoadTheme() {
        if (isset($this->config['theme']) && !empty($this->config['theme'])) {
            WB::$layout = isset(WB::$layout) ? WB::$layout : 'main';
            if (file_exists(WB_APPLICATION . '/themes/' . $this->config['theme'] . '/layouts/' . WB::$layout . '.php')) {
                return WB_APPLICATION . '/themes/' . $this->config['theme'] . '/layouts/' . WB::$layout . '.php';
            } else {
                throw new WException('Invalid layout or theme given.');
            }
        } else {
            throw new WException('Invalid theme given.');
        }
    }

}
