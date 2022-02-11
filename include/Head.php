<?php
class Head {
    public static function setHead($title) {
        echo <<<EOF
        <!doctype html>
        <html lang="ru" theme="dark">
            <head>
                <meta charset="UTF-8">
                <meta content="IE=Edge" http-equiv="X-UA-Compatible">
                <meta name="viewport" content="width=device-width, user-scalable=yes">
                <title>$title</title>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
                <link rel="stylesheet" type="text/css" href="styles/style.css">
            </head>
            <body>
                <h2>$title</h2>
        EOF;
    }

    public static function endHead() {
        echo <<<EOF
        <br>
                <footer align="right">
                    <p>© vgdn1942, 2021-2022</p>
                </footer>
            </body>
        </html>
        EOF;
    }
}
