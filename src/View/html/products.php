<?php
    echo $header(["shop" => $shop]);
    
?>
        
<!-- //add new product form with bootstrap styling -->
<form action="/shop/<?=$shop['id']?>/add/product" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="product_name">Product Name</label>
        <input type="text" name="product_name" id="product_name" class="form-control">
    </div>
    <div class="form-group">
        <label for="product_description">Product Description</label>
        <textarea name="product_description" id="product_description" cols="30" rows="10" class="form-control"></textarea>
    </div>
    <div class="form-group">
        <label for="product_price">Product Price</label>
        <input type="number" name="product_price" id="product_price" class="form-control">
    </div>
    <div class="form-group">
        <label for="product_image">Product Image</label>
        <input type="file" name="product_image" id="product_image" class="form-control">
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Add Product</button>
    </div>