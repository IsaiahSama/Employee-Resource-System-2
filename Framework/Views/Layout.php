<?php

namespace Framework\Views;

class Layout
{
    public static string $structure = "App/Views/templates/structure.php";

    public static function render($view, $data = []) {
        $content = "App/Views/pages/{$view}.php";

        if (!file_exists($content)) {
            $content = "App/Views/partials/{$view}.php";
        }

        $title = isset($data['title']) ? $data['title'] : "Application Page";
        extract($data);

        require_once self::$structure;
    }

    public static function contentRender($view, $data = []) {
        extract($data);

        if (file_exists("App/Views/partials/{$view}.php")) {
            require_once "App/Views/partials/{$view}.php";
        }
        else{
            require_once "App/Views/pages/{$view}.php";
        }
    }
}
