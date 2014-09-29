<?php

class Store extends WContainer {

    public $data = array();

    public function __construct() {
        $services = array(
            'MOD_CLASS' => 'Data'
        );
        foreach ($services as $key => $value) {
            parent::set($key, $value);
        }
    }

    public function __set($key, $value) {
        $this->data[$key] = $value;
    }

    public function __get($key) {
        return isset($this->data[$key]) ? $this->data[$key] : NULL;
    }

    public function __unset($key) {
        unset($this->data[$key]);
    }

}

?>
