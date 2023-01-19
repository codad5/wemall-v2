<?php

 use Codad5\Wemall\Controller\V1\Products;
 use Codad5\Wemall\Helper\Helper;
 use \Trulyao\PhpRouter\Router as ShopRouter;
 use \Trulyao\PhpRouter\HTTP\Response as Response;
 use \Trulyao\PhpRouter\HTTP\Request as Request;
 use \Codad5\Wemall\Controller\V1\{Lists, Users, Shops};
 use \Codad5\Wemall\Helper\Validator as Validator;
 use \Codad5\Wemall\View\V1 as View;
 $router = new ShopRouter(__DIR__ . "/src/view/", "/", '/shop');

$router->get('/', function () {
    echo "mean";
});

//shop delete route
$router->get('/:id/delete',[Helper::class, "redirect_if_shop_does_not_exist"],[Helper::class, "redirect_if_logged_out"],[Helper::class, "redirect_if_user_is_not_shop_owner"],function($req, $res){
    try{
        Shops::delete($req);

        return $res->redirect('/home?warn=shop deleted');
    }catch(Exception $e){
        return $res->redirect('/home?error=shop not deleted&info=' . $e->getMessage());
    }
});

//to create a shop
$router->post('/create', [Helper::class, "redirect_if_logged_out"], function($req, $res){
    try{
    $shop = new Shops;
    $shop->create($req);
    return $res->redirect('/home?success=shop created');
    }catch(Exception $e){
        return $res->redirect('/home?error='.$e->getMessage());

    }
});
# Display a shop
$router->get('/:id', [Helper::class, "redirect_if_shop_does_not_exist"], [Helper::class, "redirect_if_logged_out"], [Helper::class, "redirect_if_user_is_not_shop_owner"], function(Request $req, Response $res){
    try{
        $shop = Shops::load($req->params('id'));
        $shop->form = View\Shop::load_html_form($shop->shop_type);
        //load add product page
        return $res->send(Helper::load_view('html/shop_home.php', ["request" => $req, "shop" => $shop->toArray()]));
    }catch(Exception $e){
        return $res->status(400)->send($e->getMessage());
    }
});
#Delete a shop product
$router->post('/:id/product/create', [Helper::class, "redirect_if_logged_out"],[Helper::class, "redirect_if_shop_does_not_exist"],[Helper::class, "redirect_if_user_is_not_shop_owner"], function($req, $res){
    try{
        ['id' => $id] = $req->params();
        $product = Products::create($req);
        return $res->redirect('/shop/'.$id.'/product?success=product created');
    }catch(Exception $e){
        return $res->redirect('/shop/'.$id.'/product?error=product not created&info=' . $e->getMessage());
    }
});
// shop all shop products
$router->route('/:id/product')
->get([Helper::class, "redirect_if_logged_out"],[Helper::class, "redirect_if_shop_does_not_exist"],[Helper::class, "redirect_if_user_is_not_shop_owner"],function($req, $res){
        try{
            //get the shop id
            ['id' => $id] = $req->params();
            $shop = Shops::load($req->params('id'))->withProducts()->toArray();
            $shop['form'] = function($product_type, $values = []){
                return View\Shop::load_html_form($product_type, ['values' => $values]);
            };
            //load add product page
            return $res->send(Helper::load_view('html/products.php', ["request" => $req, "shop" => $shop, "products" => $shop['products']]));
        }catch(Exception $e){
            return $res->redirect('/home?error='.$e->getMessage());

        }
})
// nothing yet
->post([Helper::class, "redirect_if_logged_out"],[Helper::class, "redirect_if_shop_does_not_exist"],function($req, $res){
    // ['product_name' => $product_name, ]
});

// to edit product
$router->route('/:id/product/:product_id/edit')
->get([Helper::class, "redirect_if_logged_out"],
    [Helper::class, "redirect_if_shop_does_not_exist"],
    [Helper::class, "redirect_if_user_is_not_shop_owner"],
    function($req, $res){
    try{
        ['id' => $id, 'product_id' => $product_id] = $req->params();
        $shop = Shops::load($req->params('id'));
        $product = $shop->findProduct($product_id)->toArray();
        $shop->form = function($product_type, $values = []){
            return View\Shop::load_html_form($product_type, ['values' => $values]);
        };
        $shop = $shop->toArray();
        $product['form_action'] = 'edit';
        
        // return $res->send(Helper::load_view('html/edit_product.php', ["request" => $req, "shop" => $shop, "product" => $product]));
        return $res->send(Helper::load_view('html/ProductForms/main_form.php', ["shop" => $shop, "values" => $product]));
    }catch(Exception $e){
        return $res->send(Helper::load_error_page($e->getCode(), $e->getMessage()));
        // return $res->redirect('/home?error='.$e->getMessage());

    }
})
->post([Helper::class, "redirect_if_logged_out"],
    [Helper::class, "redirect_if_shop_does_not_exist"],
    [Helper::class, "redirect_if_user_is_not_shop_owner"],
    function(Request $req, $res){
    try{
        ['id' => $id, 'product_id' => $product_id] = $req->params();
        $product = Products::edit($req);
        exit;
        // return $res->send(Helper::load_view('html/edit_product.php', ["request" => $req, "shop" => $shop, "product" => $product]));
        // return $res->send(Helper::load_view('html/ProductForms/main_form.php', ["shop" => $shop, "values" => $product]));
    }catch(Exception $e){
        echo "<pre>";
        var_dump($e);
        exit;
        // return $res->send(Helper::load_error_page($e->getCode(), $e->getMessage()));
        // return $res->redirect('/home?error='.$e->getMessage());

    }
});
// product create route











$export = $router;