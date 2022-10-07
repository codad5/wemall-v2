<?php
namespace Codad5\Wemall\Controller\V1;
use Codad5\Wemall\Controller\V1\ProductType\Clothing;
use Codad5\Wemall\Controller\V1\ProductType\ProductInterface;
use Codad5\Wemall\Helper\CustomException;

Class  Products
{
    private Shops|array $shop;
    private Shops|array $user;
    private array $data;
    protected ProductInterface $shop_type;
    protected array $shop_types = ["clothing" => function($shop, $user, $data){
        return new Clothing($shop, $user, $data);
    }];

    public function __construct(Shops|array $shop, Users|array $user, array $data)
    {
        $this->shop = (array) $shop;
        $this->user = (array) $user;
        $this->shop_type = $this->assign_shop_type_object($shop['shop_type']);
    }

    protected function assign_shop_type_object(string $type)
    {
        if(!array_key_exists($type, $this->shop_types)){
            throw new CustomException('Invalid Shop Type', 303);
        }
        return $this->shop_types[$type]($this->shop, $this->user, $this->data);
    }
    

    
    
}