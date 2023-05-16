<?php

use Codad5\Wemall\Controller\V1\Products;
use Codad5\Wemall\Libs\Middleware;
use Codad5\Wemall\Libs\ViewLoader;
use Trulyao\PhpRouter\Router as ShopRouter;
use Trulyao\PhpRouter\HTTP\Response as Response;
use Trulyao\PhpRouter\HTTP\Request as Request;
use Codad5\Wemall\Controller\{ProductController, ShopController};
use Codad5\Wemall\View\V1 as View;
$router = new ShopRouter(__DIR__ . "/src2/view/", "/", '/shop');

$router->run([Middleware::class, "redirect_if_logged_out"]);
$router->run([Middleware::class, "redirect_if_shop_does_not_exist"]);
$router->run([Middleware::class, "redirect_if_user_is_not_shop_owner"]);



$router->get('/', function () {
    echo "mean";
});

//shop delete route
$router->delete('/:id/delete',function($req, $res){
    try{

        return $res->redirect('/home?warn=shop deleted');
    }catch(Exception $e){
        return $res->redirect('/home?error=shop not deleted&info=' . $e->getMessage());
    }
});


# Display a shop
$router->get('/:id',[ShopController::class, 'index']);
# add a new admin
$router->post('/:id/admin/add', [Middleware::class, "redirect_if_user_is_not_super_admin"], [ShopController::class, 'add_admin_to_shop']);

#Delete a shop product
$router->post('/:id/product/create', [ProductController::class, 'upload_product']);
// shop all shop products
$router->route('/:id/product')
    ->get([ShopController::class, 'product_view'])
// nothing yet
    ->post(function($req, $res){
        // ['product_name' => $product_name, ]
    });

$router->route('/:id/settings')
    ->get([ShopController::class, 'settings_view'])
// nothing yet
    ->post(function($req, $res){
        // ['product_name' => $product_name, ]
    });


// to edit product
$router->route('/:id/product/:product_id/edit')
    ->get([ProductController::class, 'edit_view'])
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