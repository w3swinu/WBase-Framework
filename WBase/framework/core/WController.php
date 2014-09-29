<?php

class WController extends WComponent{

    public function init() {
    }

    public function render($file = '', $data = array()) {
        $reflector = new ReflectionClass(get_called_class());
        $fn = $reflector->getFileName();
        $filename = dirname($fn) . '/../view/' . get_called_class() . '/' . $file . '.php';
        if (file_exists($filename)) {
            $content = $this->renderToString($filename, $data);
            $html = $this->renderToString(WB::app()->LoadTheme(), array('content' => $content));
            echo $html;
        } else {
            throw new WException('Invalid view file "' . $file . '" of controller "' . get_called_class() . '".');
        }
    }

    public function renderToString($file, $vars = null) {
        if (is_array($vars) && !empty($vars)) {
            extract($vars);
        }
        ob_start();
        include $file;
        return ob_get_clean();
    }

}
