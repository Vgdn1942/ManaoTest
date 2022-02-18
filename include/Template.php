<?php

class Template {

    /**
     * @param $path
     * @param $data
     * @return void
     */
    public static function showTemplate($path, $data = NULL) {
        session_start();
        if (!empty($_SESSION['login']) && $path === 'html/head.tpl') {
            $data += ['hello' => "Hello, " . $_SESSION['name']];
            $htmlHello = self::getHtml('html/hello.tpl', $data);
            $htmlData = self::getHtml($path, $data);
            echo $htmlHello . $htmlData;
        } else {
            $html = self::getHtml($path, $data);
            echo $html;
        }
    }

    /**
     * @param $path
     * @param $data
     * @return false|string
     */
    private static function getHtml($path, $data) {
        if ($data !== NULL) {
            foreach ($data as $key => $value) {
                $$key = $value;
            }
        }
        ob_start();
        require($path);
        return ob_get_clean();
    }
}