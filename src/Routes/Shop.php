<?php

use Codad5\Wemall\Libs\Middleware;
use Trulyao\PhpRouter\Router as ShopRouter;
use Codad5\Wemall\Controller\{ProductController, ShopController};
$router = new ShopRouter(__DIR__ . "/src/view/", "/", '/shop');

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


# add a new admin
$router->post('/:id/admin/add', [Middleware::class, "redirect_if_user_is_not_super_admin"], [ShopController::class, 'add_admin_to_shop']);
# Delete an admin
$router->post('/:id/admin/delete', [Middleware::class, "redirect_if_user_is_not_super_admin"], [ShopController::class, 'delete_admin_from_shop']);
# Display a shop
$router->get('/:id',[ShopController::class, 'index']);
# delete a product
$router->post('/:id/product/:product_id/delete', [ProductController::class, 'delete_product']);
#Create a shop product
$router->post('/:id/product/create', [ProductController::class, 'upload_product']);
# show all shop products
$router->route('/:id/product')->get([ShopController::class, 'product_view']);
# to edit product
$router->route('/:id/product/:product_id/edit')
->get([ProductController::class, 'edit_view'])
->post([ProductController::class, 'update']);


# Shop settings
$router->route('/:id/settings')->get([ShopController::class, 'settings_view']);


$export = $router;