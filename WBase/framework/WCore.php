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
        self::$app->path = NULL;
        return self::$app;
    }

    public static function setApplication($obj) {
        self::$app = $obj;
    }

    public static function autoload($className) {
        if (isset(self::$_coreClasses[strtolower($className)])) {
            include(dirname(__FILE__) . self::$_coreClasses[strtolower($className)]);
        } elseif (isset(self::app()->customclasses[strtolower($className)])) {

            include(self::app()->customclasses[strtolower($className)]);
        }
    }

    public static function registerAutoloader($callback) {
        spl_autoload_unregister(array('WCore', 'autoload'));
        spl_autoload_register($callback);
        spl_autoload_register(array('WCore', 'autoload'));
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
