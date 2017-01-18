<?php

class WController extends WComponent {

    private function LoadTheme() {
        if (isset(WB::app()->config['theme']) && !empty(WB::app()->config['theme'])) {
            WB::$layout = isset(WB::$layout) ? WB::$layout : 'main';
            if (file_exists(WB_APPLICATION . '/themes/' . WB::app()->config['theme'] . '/layouts/' . WB::$layout . '.php')) {
                return WB_APPLICATION . '/themes/' . WB::app()->config['theme'] . '/layouts/' . WB::$layout . '.php';
            } else {
                throw new WException('Invalid layout or theme given.');
            }
        } else {
            throw new WException('Invalid theme given.');
        }
    }

    public function render($file = '', $data = array()) {

        $reflector = new ReflectionClass(get_called_class());
        $fn = $reflector->getFileName();
        $filename = dirname($fn) . '/../view/' . get_called_class() . '/' . $file . '.php';
        if (file_exists($filename)) {
            $content = $this->renderToString($filename, $data);
            $html = $this->renderToString($this->LoadTheme(), array('content' => $content));
            echo $html;
        } else {
            throw new WException('Invalid view file "' . $file . '" of controller "' . get_called_class() . '".');
        }
    }

    public function renderToString($file, $vars = null) {
        if (is_array($vars) && !empty($vars)) {
            extract($vars);
        }
        $themeurl = WB::app()->themeurl;
        ob_start();
        include $file;
        return ob_get_clean();
    }

}
