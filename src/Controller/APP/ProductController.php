<?php

namespace Codad5\Wemall\Controller\APP;

use Codad5\PhpRouter\HTTP\Request;
use Codad5\PhpRouter\HTTP\Response;
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Exceptions\ProductException;
use Codad5\Wemall\Libs\Exceptions\ShopException;
use Codad5\Wemall\Libs\Validator;
use Codad5\Wemall\Libs\ViewLoader;
use Codad5\Wemall\Models\Product;
use Codad5\Wemall\Models\Shop;
use Codad5\Wemall\View\V1 as View;

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
    static function delete_product(Request $req, Response $res): Response
    {
        ['id' => $shopId, 'product_id' => $product_id] = $req->params();
        try{
            $shop = Shop::find($shopId);
            $product = $shop->findProduct($product_id);
            if(!$product) throw new ProductException('Product not found');
            $product = $product->delete();
            return $res->redirect("/shop/$shopId/product?warn=Product successfully deleted");
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
            return $res->send([ViewLoader::load_error_page($e->getCode(), $e->getMessage())]);
            // return $res->redirect('/home?error='.$e->getMessage());

        }
    }
    static function update(Request $req, Response $res)
    {
        ['id' => $shopId, 'product_id' => $product_id] = $req->params();
        try{
            $shop = Shop::find($shopId);
            $product = $shop->findProduct($product_id);
            if(!$product) throw new ProductException('Product not found');
            if (!Validator::validate_product_creation_data($shop->type, $req, true))
                throw new CustomException("Error is shop Data", 300);
            $product = $product->update($shop, $req->body());
            return $res->redirect("/shop/$shopId/product?success=Product successfully created");
        }catch(\Exception $e){
            return $res->redirect("/shop/$shopId/product?error=".$e->getMessage());

        }
    }

}