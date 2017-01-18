<?php

class Sample extends WController {

    public $store;

    public function __construct() {
        $this->store = new Store();
    }
    
    public function init(){}

    public function Index() {
        WB::$layout = 'main'; // specify only if layout is different, Default "main"
        $model = $this->store->get('MOD_CLASS');
        $this->render('index', array('info' => 'version 1.0'));
    }
    
    public function about(){
        $this->render('about');
    }
    
    public function error(){
        echo 'Page not found';
    }

}
