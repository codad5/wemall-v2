<?php

use Codad5\Wemall\Libs\APIMiddleWare;
use Codad5\Wemall\Libs\ViewLoader;
use Codad5\PhpRouter\Router as ShopRouter;
use Codad5\PhpRouter\HTTP\Response as Response;
use Codad5\PhpRouter\HTTP\Request as Request;
use Codad5\Wemall\Controller\API\{ProductController, ShopController};
use Codad5\Wemall\View\V1 as View;
$router = new ShopRouter(__DIR__ . "/src2/view/",  '/shop');

//$router->run([Middleware::class, "redirect_if_logged_out"]);
//$router->run([Middleware::class, "redirect_if_shop_does_not_exist"]);
//$router->run([Middleware::class, "redirect_if_user_is_not_shop_owner"]);



$router->get('/', function (Request $req, Response $res) {
   $res->send([
       "home" => 'test'
   ]);
});


# Display a shop
$router->get('/:id',[ShopController::class, 'get_shop']);
# list all shop of a particular type
$router->get('/list/:type',[ShopController::class, 'search_shop']);
$router->get('/:id/owner',[ShopController::class, 'get_owner']);

# add a new admin
$router->post('/:id/admin/add', [APIMiddleWare::class, "redirect_if_user_is_not_super_admin"], [ShopController::class, 'add_admin_to_shop']);
# Delete an admin
$router->post('/:id/admin/delete', [APIMiddleWare::class, "redirect_if_user_is_not_super_admin"], [ShopController::class, 'delete_admin_from_shop']);

#Delete a shop product
$router->post('/:id/product/create', [ProductController::class, 'upload_product']);
// show all shop products
$router->route('/:id/product')->get([ShopController::class, 'product_view']);
# Shop settings
$router->route('/:id/settings')->get([ShopController::class, 'settings_view']);


// to edit product
$router->route('/:id/product/:product_id/edit')
->get([ProductController::class, 'edit_view'])
->post([ProductController::class, 'update']);



$export = $router;