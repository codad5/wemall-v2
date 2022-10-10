
    <?=$header(["shop" => $shop])?>
        
<!-- //add new product form with bootstrap styling -->

<section class="my-3" id="accordion">
  <div class="bd-heading sticky-xl-top align-self-start mt-5 mb-3 mt-xl-0 mb-xl-2" style="background:#fff;z-index:10;">
    <h3>Products Settings</h3>
    <a class="d-flex align-items-center" href="">Products Settings</a>
  </div>
  <div>
    <div class="bd-example">
      <div class="accordion" id="accordionExample">
        <!-- BEGIN OF SECON ACCORDITION ITEM {ADD PRODUCTS} -->
        <div class="accordion-item">
          <h4 class="accordion-header" id="headingOne">
            <button class="accordion-button collapsed" type="button" id="accordion_add_product_btn" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
              Add New Product
            </button>
          </h4>
          <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
            <div class="accordion-body">
              <article class="my-3" id="validation">
                <div class="bd-heading sticky-xl-top align-self-start mt-5 mb-3 mt-xl-0 mb-xl-2">
                  <h3>Add New Product</h3>
                  <!-- <a class="d-flex align-items-center" href="../forms/validation/">Documentation</a> -->
                </div>
                <div>
                  <div class="bd-example">
                    <?=$include('html/ProductForms/main_form.php', [
                      "shop" => $shop
                    ])?>
                  </div>
                </div>
              </article>
            </div>
          </div>
        </div>
        <!-- END OF FIRST ACCORDITION ITEM {ADD PRODUCT} -->
        
        <!-- BEGIN OF SECON ACCORDITION ITEM {ALL PRODUCTS} -->
        <div class="accordion-item">
            <h4 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" id="product_table" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                All Products
              </button>
            </h4>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <div class="table-responsive">
                  <?php 
                  if(isset($products) && count($products) > 0):
                    $count = 1;
                    ?>
                    <table class="table table-striped table-hover table-responsive">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Currenct Sell price</th>
                        <th>Total quantity</th>
                        <th>added by</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody class="product_table_body">
                        
                  <?php
                    foreach($products as $product):
                  ?>
                  <tr>
                    <td><?=$count?></td>
                    <td><?=$product['name']?></td>
                    <td><?=$product['price']?></td>
                    <td><?=$product['sell_price']?></td>
                    <td><?=$product['quantity']?></td>
                    <td><?=$product['created_by']?></td>
                    <td><button type="button" class="alter_product_btn edit_product_btn btn btn-primary" data-product-action="edit" data-product-id="<?=$product['product_id']?>">EDIT</button></td>
                    <td><button type="button" class="alter_product_btn delete_product_btn btn btn-danger" data-product-action="delete" data-product-id="<?=$product['product_id']?>">DELETE</button></td>
                </tr>
                <?php
                  endforeach;
                ?>
                <!-- script to  load the edit form through ajax and delete the edit form -->
                <script>
                  $(document).ready(function(){
                    $('.edit_product_btn').click(function(){
                      //click on element with classname edit_product_cnt after 3sec
                      setTimeout(function(){
                        $('.edit_product_cnt').click();
                      }, 3000);
                      var product_id = $(this).attr('data-product-id');
                      var product_action = $(this).attr('data-product-action');
                      $('.product_table_body').load('<?=$product['name']?>products/edit_product_form/'+product_id+'/'+product_action, 
                      function(){
                        // alert('load was performed');
                      });
                    });
                  });
                </script>
                <!-- end of script to  load the edit form through ajax and delete the edit form -->
                                      
                        
                    </tbody>
                    </table>
                    <?php else: ?>
                      <h2>
                        No Product yet!
                      </h2>
                    <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h4 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" id="product_edit" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Edit Product
              </button>
            </h4>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
              <div class="accordion-body" id="edit_product_cnt">
              </div>
            </div>
          </div>
        </div>
        </div>
      </div>
</section>