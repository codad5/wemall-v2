<?php

use Codad5\Wemall\Controller\V1\Products;
use Codad5\Wemall\Libs\Middleware;
use Codad5\Wemall\Libs\ViewLoader;
use Trulyao\PhpRouter\Router as ShopRouter;
use Trulyao\PhpRouter\HTTP\Response as Response;
use Trulyao\PhpRouter\HTTP\Request as Request;
use Codad5\Wemall\Controller\V1\{ShopController};
use Codad5\Wemall\View\V1 as View;
$router = new ShopRouter(__DIR__ . "/src2/view/", "/", '/shop');

$router->run([Middleware::class, "redirect_if_logged_out"]);
$router->run([Middleware::class, "redirect_if_shop_does_not_exist"]);
$router->run([Middleware::class, "redirect_if_user_is_not_shop_owner"]);
$router->run(function (){
});


$router->get('/', function () {
    echo "mean";
});

//shop delete route
$router->get('/:id/delete',function($req, $res){
    try{
        ShopController::delete($req);

        return $res->redirect('/home?warn=shop deleted');
    }catch(Exception $e){
        return $res->redirect('/home?error=shop not deleted&info=' . $e->getMessage());
    }
});


# Display a shop
$router->get('/:id', function(Request $req, Response $res){
    try{
        $shop = ShopController::load($req->params('id'));
        $shop->form = View\Shop::load_html_form($shop->shop_type);
        //load add product page
        return $res->send(ViewLoader::load('html/shop_home.php', ["request" => $req, "shop" => $shop->toArray()]));
    }catch(Exception $e){
        return $res->status(400)->send($e->getMessage());
    }
});
# add a new admin
$router->post('/:id/admin/add', function (Request $request, Response $response)
{
    $shop_id = $request->params('id');
    try {
        ShopController::add_user_to_shop($shop_id, $request->body('email') ??  '');
        return $response->redirect("/shop/$shop_id/edit?success=Admin added");

    }catch (Exception $e)
    {
        return $response->redirect("/shop/$shop_id/edit?error=".$e->getMessage());
    }
});

#Delete a shop product
$router->post('/:id/product/create', function(Request $req, Response $res){
    try{
        ['id' => $id] = $req->params();
        $product = Products::create($req);
        return $res->redirect('/shop/'.$id.'/product?success=product created');
    }catch(Exception $e){
        return $res->redirect('/shop/'.$req->params().'/product?error=product not created&info=' . $e->getMessage());
    }
});
// shop all shop products
$router->route('/:id/product')
    ->get(function($req, $res){
        try{
            //get the shop id
            ['id' => $id] = $req->params();
            $shop = ShopController::load($req->params('id'))->withProducts()->toArray();
            $shop['form'] = function($product_type, $values = []){
                return View\Shop::load_html_form($product_type, ['values' => $values]);
            };
            //load add product page
            return $res->send(ViewLoader::load('html/products.php', ["request" => $req, "shop" => $shop, "products" => $shop['products']]));
        }catch(Exception $e){
            return $res->redirect('/home?error='.$e->getMessage());

        }
    })
// nothing yet
    ->post(function($req, $res){
        // ['product_name' => $product_name, ]
    });

$router->route('/:id/edit')
    ->get(function(Request $req, Response $res){
        try{
            //get the shop id
            ['id' => $id] = $req->params();
            $shop = ShopController::load($req->params('id'))->withAdmins()->toArray();
            //load add product page
            return $res->send(ViewLoader::load('html/shop_setting.php', ["request" => $req, "shop" => $shop, "products" => $shop['products']]));
        }catch(Exception $e){
            return $res->redirect('/home?error='.$e->getMessage());

        }
    })
// nothing yet
    ->post(function($req, $res){
        // ['product_name' => $product_name, ]
    });


// to edit product
$router->route('/:id/product/:product_id/edit')
    ->get(function($req, $res){
        try{
            ['id' => $id, 'product_id' => $product_id] = $req->params();
            $shop = ShopController::load($req->params('id'));
            $product = $shop->findProduct($product_id)->toArray();
            $shop->form = function($product_type, $values = []){
                return View\Shop::load_html_form($product_type, ['values' => $values]);
            };
            $shop = $shop->toArray();
            $product['form_action'] = 'edit';

            // return $res->send(ViewLoader::load('html/edit_product.php', ["request" => $req, "shop" => $shop, "product" => $product]));
            return $res->send(ViewLoader::load('html/ProductForms/main_form.php', ["shop" => $shop, "values" => $product]));
        }catch(Exception $e){
            return $res->send(ViewLoader::load_error_page($e->getCode(), $e->getMessage()));
            // return $res->redirect('/home?error='.$e->getMessage());

        }
    })
    ->post(function(Request $req, $res){
        try{
            ['id' => $id, 'product_id' => $product_id] = $req->params();
            $product = Products::edit($req);
            exit;
            // return $res->send(ViewLoader::load('html/edit_product.php', ["request" => $req, "shop" => $shop, "product" => $product]));
            // return $res->send(ViewLoader::load('html/ProductForms/main_form.php', ["shop" => $shop, "values" => $product]));
        }catch(Exception $e){
            echo "<pre>";
            var_dump($e);
            exit;
            // return $res->send(Helper::load_error_page($e->getCode(), $e->getMessage()));
            // return $res->redirect('/home?error='.$e->getMessage());

        }
    });



$export = $router;