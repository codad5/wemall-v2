<?php
namespace Codad5\Wemall\Controller\V1\ProductType;

interface ProductInterface
{
    public function validate_product_data();
    public function assign_product_data($unique_id);
    public function create_product($data);
    public static function get_all_shop_product($shop_id);
    public static function get_product_by_id($product_id);

}