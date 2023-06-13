<?php

namespace Codad5\Wemall\Controller\API;

use Codad5\Wemall\Enums\ShopType;
use Codad5\Wemall\Enums\StatusCode;
use Codad5\Wemall\Enums\UserError;
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Exceptions\ProductException;
use Codad5\Wemall\Libs\Exceptions\ShopException;
use Codad5\Wemall\Libs\ResponseHandler;
use Codad5\Wemall\Libs\Validator;
use Codad5\Wemall\Libs\ViewLoader;
use Codad5\Wemall\Models\ProductImage;
use Codad5\Wemall\View\V1 as View;
use Codad5\Wemall\Models\Product;
use Codad5\Wemall\Models\Shop;
use Codad5\PhpRouter\HTTP\Request;
use Codad5\PhpRouter\HTTP\Response;

class ProductController
{
    static function upload_product(Request $req, Response $res): Response
    {
        $shopId = $req->params('id');
        try{
            $shop = Shop::find($shopId);
            if(!$shop) throw new ShopException('Shop not found');
            if (!Validator::validate_product_creation_data($shop->type, $req))
                throw new CustomException("Error is shop Data", 300);
            $product = Product::create($shop, $req->body());
            return $res->redirect("/shop/$shopId/product?success=Product successfully created");
        }catch(\Exception $e){
            return $res->redirect("/shop/$shopId/product?error=".$e->getMessage());

        }
    }

    static function edit_view(Request $req, Response $res): Response
    {
        try{
            ['id' => $id, 'product_id' => $product_id] = $req->params();
            $shop = Shop::find($id);
            $product = $shop->findProduct($product_id)->toArray();
            $shop = $shop->toArray();
            $shop['form'] = function($product_type, $values = []){
                return View\Shop::load_html_form($product_type, ['values' => $values]);
            };
            $product['form_action'] = 'edit';

            // return $res->send(ViewLoader::load('html/edit_product.php', ["request" => $req, "shop" => $shop, "product" => $product]));
            return $res->send(ViewLoader::load('html/ProductForms/main_form.php', ["shop" => $shop, "values" => $product]));
        }catch(\Exception $e){
            return $res->send(ViewLoader::load_error_page($e->getCode(), $e->getMessage()));
            // return $res->redirect('/home?error='.$e->getMessage());

        }
    }
    static function get_product(Request $req, Response $res)
    {
        ['id' => $product_id] = $req->params();
        try{
            $product = Product::find($product_id);
            if(!$product) throw new ProductException("Product not found for id ($product_id)", 404);
            return ResponseHandler::sendSuccessResponse($res, $product->toArray(), ['cache_data' => $req->path()]);
        }catch (\Exception $exception)
        {
            return ResponseHandler::sendErrorResponse($res, $exception->getMessage(), $exception->getCode());
        }
    }
    static function get_all_product(Request $req, Response $res)
    {
        try{
            $type_ = $type = $req->params("type");
            if(isset($type)) $type = ShopType::tryFrom($type_);
            if (!$type) throw new CustomException("Product type ({$type_}) not supported", UserError::INVALID_TYPE);
            $products = Product::all($type);
            if(!$products) throw new ProductException('Product not found');
            $products = array_map(function($data) { unset($data['id']); return [...$data, 'images' => ProductImage::getImaagesFromProduct($data['product_id'])];}, $products);
            return ResponseHandler::sendSuccessResponse($res, $products, ['cache_data' => $req->path()]);
        }catch (\Exception $exception)
        {
            return ResponseHandler::sendErrorResponse($res, $exception->getMessage(), $exception->getCode());
        }
    }
    static function search_product(Request $req, Response $res)
    {
        try{
            $type = ShopType::tryFrom($req->params('type'));
//            var_dump();
            if(!$type) throw new ProductException("Invalid shop type ".$req->params('type'), UserError::INVALID_TYPE);
            $products = Product::search([...$req->query()], $type);
            return ResponseHandler::sendSuccessResponse($res, $products, ['cache_data' => $req->path(), "query" =>  $req->query()]);
        }catch (\Exception $exception)
        {
            return ResponseHandler::sendErrorResponse($res, $exception->getMessage(), $exception->getCode());
        }
    }

}