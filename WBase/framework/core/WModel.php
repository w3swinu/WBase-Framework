<?php

class WModel{
       
    public $_db;
    public function __construct() {
        $this->_db = WB::app()->db;
    }

    public function CreateQuery($sql = '') {
        if (!empty($sql)) {
            $result = mysqli_query($this->_db, $sql);
            return $result;
        }  else {
            throw new WException('Empty string is given.');
        }
    }

}
