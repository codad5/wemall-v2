<?php
namespace Codad5\Wemall\Controller\V1;

use Codad5\Wemall\Model\Shop as Shop;
use Codad5\Wemall\Helper\CustomException as CustomException;

class Shops
{
    private Shop $shop;
    protected string $id;
    protected string $name;
    protected string $description;
    protected Users $created_by;

   public function __construct($name, $description, Users $created_by)
   {
    $this->shop = new Shop;
    $this->name = $name;
    $this->description = $description;
    $this->created_by = $created_by;
   }

   public function validate_shop_data(){
    if(empty($this->name)){
        return new CustomException("shop name required", 303);
    }
    if(empty($this->description)){
        return new CustomException("shop description required", 303);
    }
    //  added validation for character free name and description to prevent cross site scripting

   }

   protected function generate_id() : string
   {
    return "shk_"
   }
}
