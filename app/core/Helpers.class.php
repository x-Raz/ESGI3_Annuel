<?php

class Helpers
{
    public static function debug($data)
    {
        if($data === false || $data === null){
            echo '<pre>';
            var_dump($data);
            echo '</pre>';
        }

        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public static function getAsset($path)
    {
        return BASE_PATH . 'app/assets/' . $path;
    }

    public static function getAdminRoute($path)
    {
        $path = rtrim($path, '/');
        return BASE_PATH . ADMIN_PATH . '/' . $path . '/';
    }

    public static function redirectBack()
    {
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $path = $_SERVER['HTTP_REFERER'];
            self::redirect($path);
        }
    }

    public static function redirect($path)
    {
        header('Location: ' . $path);
        exit();
    }

    public static function error500()
    {
        header('HTTP/1.1 500 Internal Server Error');
    }

    public static function error403($message = null)
    {
        if (is_array($message)) {
            $message = json_encode($message);
        }

        header('HTTP/1.1 403 Forbidden');
        die($message);
    }

    public static function error404(){
        $errorManager = new ErrorController();
        $errorManager->error404();
        die;
    }
}
