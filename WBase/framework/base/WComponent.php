<?php

class WComponent {

    private $data;

    public function __set($key, $value) {
        $this->data[$key] = $value;
    }

    public function __get($key) {
        return isset($this->data[$key]) ? $this->data[$key] : NULL;
    }

    public function __unset($key) {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }

    public function attach($key, $value) {
        return $this->data[$key] = $value;
    }

    public function detach($key) {
        if (isset($this->data[$key]))
            $this->data[$key] = NULL;
    }

    public function remove($key) {
        if (isset($this->data[$key]))
            unset($this->data[$key]);
    }

    public function getAttached($key) {
        if (isset($this->data[$key]))
            return $this->data[$key];
    }

}
