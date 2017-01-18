<?php

abstract class WContainer {
    /*
     * @var array classes stores class names
     */

    public $classes = [];
    /*
     * @var array args stores passed arguments to the constructor
     */
    public $args = [];

    /*
     * abstract function to register classes
     */

    abstract public function __construct();

    /*
     * set
     * It is used to register class
     * @param id for class
     * @param class for class name
     * @param args array for class constructor
     */

    public function set($id, $class, array $args = []) {
        if (class_exists($class)) {
            $this->classes[$id] = $class;
            $this->args[$id] = $args;
        } else {
            throw new WException("Invalid class name when instantiating \"$class\".");
        }
    }

    /*
     * get
     * It is used to get object of registered class
     * $param id of registered class
     * @return object of registered class
     */

    public function get($id, array $params = NULL) {
        $instance = '';
        $dependencies = array();
        $class = $this->classes[$id];
        $args = isset($params) ? $params : $this->args[$id];
        if (class_exists($class)) {
            $reflection = new ReflectionClass($class);
            $constructor = $reflection->getConstructor();
            
            if ($constructor !== null) {
                if (!empty($args)) {

                    foreach ($constructor->getParameters() as $param) {
                        $dependencies[] = $param;
                    }

                    if (count($dependencies) >= count($args)) {
                        $instance = $reflection->newInstanceArgs($args);
                    } else {
                        throw new WException("Invalid number of parameteres when instantiating \"$class\".");
                    }
                } else {
                    $instance = $reflection->newInstance();
                }
            } else {
                $instance = $reflection->newInstanceWithoutConstructor();
            }
            return $instance;
        } else {
            throw new WException("Invalid class name when instantiating \"$class\".");
        }
    }

    /*
     * getClasses
     * Function to get all registered classes
     * @return array of all classes with id
     */

    public function getClasses() {
        return $this->classes;
    }

    /*
     * getArgs
     * Function to get all parameters passed
     * @return array of classes with parameters
     */

    public function getArgs() {
        return $this->args;
    }

}
