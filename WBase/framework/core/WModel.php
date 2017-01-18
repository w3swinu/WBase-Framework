<?php

class WModel {
    
    private $db;
    
    public function __construct(){
        $this->db = WB::app()->db;
    }

    public function RunQuery($sql = '') {
       
        if (!empty($sql)) {
            if (isset($this->db)) {
                $result = mysqli_query($this->db, $sql);
                return $result;
            } else {
                throw new WException('Define a db in config.');
            }
        } else {
            throw new WException('Empty string is given.');
        }
    }

}
