# wemall-v2

This is an open sources CMS to make full stack ecommerce development very easy 

## ADDING A NEW SHOP/PRODUCT TYPE
- Go to [src/enums/ShopType.php](/src/enums/ShopType.php) and add your new enum case
- Right after that go add the sql query case in the `getProductInsertSqlQuery` method in the same file
- Then add the form validation in the `validateProductFormField` method
- GO TO [asset/db.sql](/assets/private/db.sql) and add the product table in the following manner 
- - The table name should be the value to the case you added followed by `_products` 
  - So if the value to the case is `car` then the table name `car_products`
  - The product table must have some column which are :
  -  - `id` - For indexing purpose
     - `product_id` - Serve as foreign key to the main `products` table
  - > Example of how the TABLE CREATION SQL STATEMENT WOULD LOOK LIKE FOR A CAR PRODUCT TYPE
  ```mysql
    CREATE TABLE `car_products` (
     `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL ,
     `product_id` varchar(11) NOT NULL ,
     'brand' varchar(11) NOT NULL ,
     `color` varchar(8) NOT NULL ,
     `mile_age` ENUM('male', 'female', 'unisex') NOT NULL,
     FOREIGN KEY (product_id) REFERENCES products(product_id)
    );
    ```
- After creation of table you would need to add the html form
- You would do this by creating a new file in [src/view/html/productForms.php](src/view/html/productForms.php) name by the product type , example for a car product would be `car.php`
- Then add the input field for each field you add with `name attribute` each to the column name in the db
  - > Example for the car example would look like this 
    ```php
    <?php
      // This PHP CODE IS TO SET DEFAULT VALUE FOR EDITING THE PRODUCTS
      if(empty($values)) $values = null;
      if(isset($values) && is_array($values))
      {
        $values = json_encode($values);
        $values = json_decode($values, false);
      }
      ?>
      <!-- For the brand input -->
      <div class="col-md-4" id="product_brand_cnt">
        <label for="car_brand" class="form-label">Car Brand</label>
        <select class="form-select" name="brand" id="car_brand" value="<?=$values?->brand ?? '' ?>">
          <option value="Volvo">Volvo</option>
          <option value="Toyota">Toyota</option>
          <option value="Mercedes">Mercedes</option>
        </select>
      </div>
      <!-- For the color input -->
      <div class="col-md-4" id="product_brand_cnt">
        <label for="car_color" class="form-label">Car color</label>
        <input type="color" class="form-control" name="color" id="car_color" value="<?=$values?->color ?? '' ?>">
      </div>
      <!-- For the color input -->
      <div class="col-md-4" id="product_brand_cnt">
        <label for="car_mile_age" class="form-label"> Mile Age</label>
        <input type="color" class="form-control" name="mile_age" id="car_mile_age" value="<?=$values?->mile_age ?? '' ?>">
      </div>
    ```
- Then You are done.