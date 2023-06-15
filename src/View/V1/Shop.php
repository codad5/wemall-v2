<?php
namespace Codad5\Wemall\View\V1;
use Codad5\Wemall\Enums\ShopType;
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\ViewLoader;

Class Shop{
    private static array $shop_type_array = ["clothing", "food", "automobile", "phones", "furniture's"];
    protected static string $type;
    public function __construct(string $type)
    {
        $this->type = $type; 
    }

    /**
     * @throws CustomException
     */
    public static function load_html_form(ShopType $type, array $data = []): string
    {
        if(!in_array($type->value, self::$shop_type_array)){
            throw new CustomException('Invalid Shop Type', 303);
        }
        return html_entity_decode(ViewLoader::load("html/ProductForms/{$type->value}.php", $data));
    }
    
    
}