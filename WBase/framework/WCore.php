<?php

defined('WB_PATH') or define('WB_PATH', dirname(__FILE__));
defined('WB_APPLICATION') or define('WB_APPLICATION', dirname($_SERVER["SCRIPT_FILENAME"]));

class WCore {

    public static $app;
    public static $layout;

    public static function CreateApplication($config = array()) {
        return self::GetComponent('WApplication', $config);
    }

    public static function GetComponent($class, $param = NULL) {
        return new $class($param);
    }

    public static function app() {
        //unset(self::$app->config);
        return self::$app;
    }

    public static function setApplication($obj) {
        self::$app = $obj;
    }

    public static function autoload($className) {
        $className = strtolower($className);
        if (isset(self::$_coreClasses[$className])) {
            require_once(dirname(__FILE__) . self::$_coreClasses[$className]);
        } elseif (isset(self::app()->customclasses[$className])) {
            require_once(self::app()->customclasses[$className]);
            unset(self::app()->customclasses[$className]);
            if(empty(self::app()->customclasses))
                unset(self::app()->customclasses);
        }
    }

    private static $_coreClasses = array(
        'wapplication' => '/base/WApplication.php',
        'wcomponent' => '/base/WComponent.php',
        'wcontroller' => '/core/WController.php',
        'wmodel' => '/core/WModel.php',
        'wcontainer' => '/core/WContainer.php',
        'wexception' => '/core/WException.php'
    );

}

spl_autoload_register(array('WCore', 'autoload'));
