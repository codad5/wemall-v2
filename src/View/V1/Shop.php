<?php
namespace Codad5\Wemall\View\V1;
use Codad5\Wemall\Helper\CustomException;
use Codad5\Wemall\Helper\Helper;

Class Shop{
    private static array $shop_type_array = ["clothing", "food", "automobile", "phones", "furnitures"];
    protected static string $type;
    public function __construct(string $type)
    {
        $this->type = $type; 
    }

    public static function load_html_form(string $type, array $data = [])
    {
        if(!in_array($type, self::$shop_type_array)){
            throw new CustomException('Invalid Shop Type', 303);
        }
        return Helper::load_view("html/ProductForms/{$type}.php", $data);
    }
    
    
}