<?php
namespace Codad5\Wemall\Libs;

use Codad5\Wemall\Libs\Helper\Helper;
use JetBrains\PhpStorm\NoReturn;

class ViewLoader {
    // to load / render php files to html
    /**
     * @param string $file path to FIle relative to the view folder
     * @param array $data - data to be passed into the file
     * @return string
     * @method string load(string $file, array $data = []) Sample usage ViewLoader::load("/html/dev.php", ['some_data" => $value])
     */
    public static function load(string $file, array $data = []) : string
    {
        $file = Helper::resolve_view($file);
        if(!file_exists($file)){
             self::load_error_page(404, "File not found");
        }
        Helper::set_notification_session($data);

        ob_start();
        $data = array_merge($data, [
            'asset' => function($file){
                return Helper::resolve_public_asset($file);
            },
            'error' => 200,
            "header" => function(array $data = [], $title = ""){
                return self::load('templates/header.php', array_merge([
                    'app_name' => $_ENV['APP_NAME'] ?? "Wemall",
                    'title' => $title 
                ], $data));
            },
            "footer" => function(){
                return self::load('templates/footer.php');
            },
            "notification" => function($message = null, $type = "success"){
                $error = self::load('templates/alerts.php', [
                    'message' => $message,
                    'type' => $type,
                    'success' => $_SESSION['messages']['success'] ?? null,
                    'errors' => $_SESSION['messages']['errors'] ?? null,
                    "info" => $_SESSION['messages']['info'] ?? null,
                    "warning" => $_SESSION['messages']['warning'] ?? null,
                    "dev_warning" => $_SESSION['messages']['dev_warning'] ?? null
                ]);
                unset($_SESSION['messages']);
                return $error;

            },
            "include" => function($file, $data = []){
                return self::load($file, $data);
            },
        ]);
        extract($data);
        require $file;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public static function load_error_page($code, $message)
    {
        $data = [
            'code' => $code,
            'message' => $message
        ];
        echo self::load('templates/error.php', $data);
        die();
    }
}
