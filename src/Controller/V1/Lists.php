<?php
namespace Codad5\Wemall\Controller\V1;

use \Codad5\Wemall\Model\Product as Product;

Class Lists{
    private Product $product;
    protected string $filter;
    protected string $keyword;
    public function __construct(string $filter, string $keyword)
    {
        $this->product = new Product();
        $this->filter = $filter;
        $this->keyword = $keyword;
    }
    
    public function get_list()
    {
        switch ($this->filter) {
            case 'id':
                return $this->product->get_product_by_id($this->keyword);
            break;
            case 'name':
                return $this->product->get_product_by_name($this->keyword);
            break;
            case 'category':
                return $this->product->get_product_by_category($this->keyword);
            break;
            case 'subcategory':
                return $this->product->get_product_by_subcategory($this->keyword);
            break;
            case 'brand':
                return $this->product->get_product_by_brand($this->keyword);
            break;
            default:
                return $this->product->get_product_by_id($this->keyword);
            break;
        }
    }
}