<?php
namespace Codad5\Wemall\Model\ProductType;

interface ProductType {
    public function __construct($id = null);
    /**
     * To convert the product object to array
     * @return array
    */
    public function toArray() : array;
    /**
     * TO create a new sample of the product
     * @param string $unique_id the product unique id created by the instance of `Codad5\Wemall\Model\Product::create` method
     * @param array $data the data of the product
     * @return bool
     */
    public function new($unique_id, array $data) : bool;
    /**
     * TO delete a product
     * @param mixed $product_id the product id
     * @return bool
     */
    public function delete($product_id = null) : bool;
    /**
     * Setup a product based of the given data
     * @param array|null $data 
     * @return ProductType
    */
    public static function use(array $data = null) : ProductType;
    /**
     * Get all the column name of a particular product in the database;
     * @return array
     */
    public static function getFieldSet() : array;
}