<?php

namespace Codad5\Wemall\Enums;

use Codad5\Wemall\Libs\Exceptions\ProductException;
use Trulyao\PhpRouter\HTTP\Request;

enum ShopType: string
{
    case clothing = 'clothing';

    public function getProductInsertSqlQuery(): string
    {
        return match ($this){
            self::clothing => "INSERT INTO {$this->value}_products  (product_id, color, gender, size) VALUES (:product_id, :color, :gender, :size)"
        };
    }

    public function getProductUpdateSqlQuery(): string
    {
        return match ($this){
            self::clothing => "INSERT INTO {$this->value}_products  (product_id, color, gender, size) VALUES (:product_id, :color, :gender, :size)"
        };
    }

    public function getProductTableName(): string
    {
        return $this->value."_products";
    }
    function getFields() : array
    {
        return match ($this){
            self::clothing => ['size', 'color', "gender"]
        };
    }

    /**
     * @throws ProductException
     */
    public function validateProductFormField(Request $req): true
    {
        switch ($this)
        {
            case self::clothing:
                if(!$req->body('color')) throw new ProductException("Color is invalid");
                if(!$req->body('gender')) throw new ProductException("Gender is invalid");
                if(!$req->body('size')) throw new ProductException("Size is invalid");
            break;
        }
        return true;
    }

}
