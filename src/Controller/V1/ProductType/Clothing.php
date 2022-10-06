<?php
namespace Codad5\Wemall\Controller\V1\ProductType;

Class Clothing implements ProductInterface
{
    private string $name;
    private string $id;
    private string $size;
    private string $color;
    private string $price;
    private string $quantity;
    private string $discount;
    private string $discount_type;
    private string $gender;

    public function __construct($shop_data){

    }

}
