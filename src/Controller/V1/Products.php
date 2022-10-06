<?php
namespace Codad5\Wemall\Controller\V1;
use Codad5\Wemall\Controller\V1\ProductType\Clothing;
use Codad5\Wemall\Controller\V1\ProductType\ProductInterface;

Class  Products
{
    private Shops|array $shop;
    private Shops|array $user;
    protected ProductInterface $shop_type;
    protected array $shop_types = ["clothing" => function($shop, $user){
        return new Clothing($shop, $user);
    }];

    public function __construct(Shops|array $shop, Users|array $user, array $data)
    {
        $this->shop = (array) $shop;
        $this->user = (array) $user;
        $this->shop_type = $this->assign_shop_type_object($shop['shop_type']);
    }

    protected function assign_shop_type_object(string $type)
    {

    }
    

    
    
}