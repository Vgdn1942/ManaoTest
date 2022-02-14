<?php

class Template {

    public static function showTemplate($template_path, $data = NULL) {
        $html = (new Template)->getHtml($template_path, $data);
        echo $html;
    }

    private function getHtml($template_path, $data) {
        if ($data !== NULL) {
            foreach ($data as $key => $value) {
                $$key = $value;
            }
        }
        ob_start();
        require($template_path);
        return ob_get_clean();
    }
}