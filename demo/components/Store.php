<?php

class Store extends WContainer {

    public function __construct() {
        $services = array(
            'MOD_CLASS' => 'Data'
        );
        foreach ($services as $key => $value) {
            parent::set($key, $value);
        }
    }

}
