<?php
    echo $header(["shop" => $shop]);
    
?>
        
<!-- //add new product form with bootstrap styling -->

    <section class="my-3" id="accordion">
      <div class="bd-heading sticky-xl-top align-self-start mt-5 mb-3 mt-xl-0 mb-xl-2" style="background:#fff;z-index:10;">
        <h3>Products Settings</h3>
        <a class="d-flex align-items-center" href="">Products Settings</a>
      </div>

      <div>
        <div class="bd-example">
        <div class="accordion" id="accordionExample">
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
    <?=$shop['form']?></div>
      </div>
    </article>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h4 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" id="product_table" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                All Products
              </button>
            </h4>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <div class="table-responsive">
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
                        

<tr>
                    <td>1</td>
                    <td>New Vintage up</td>
                    <td>1200</td>
                    <td>1195</td>
                    <td>10</td>
                    <td>aniezeoformic@gmail.com</td>
                    <td><button type="button" class="alter_product_btn edit_product_btn btn btn-primary" data-product-action="edit" data-product-id="624dca46d4cea7.38412932">EDIT</button></td>
                    <td><button type="button" class="alter_product_btn delete_product_btn btn btn-danger" data-product-action="delete" data-product-id="624dca46d4cea7.38412932">DELETE</button></td>
                </tr><tr>
                    <td>2</td>
                    <td>Sweet Sweater</td>
                    <td>700</td>
                    <td>650</td>
                    <td>20</td>
                    <td>aniezeoformic@gmail.com</td>
                    <td><button type="button" class="alter_product_btn edit_product_btn btn btn-primary" data-product-action="edit" data-product-id="624dcab521a429.71215399">EDIT</button></td>
                    <td><button type="button" class="alter_product_btn delete_product_btn btn btn-danger" data-product-action="delete" data-product-id="624dcab521a429.71215399">DELETE</button></td>
                </tr><tr>
                    <td>3</td>
                    <td>Tus</td>
                    <td>12000</td>
                    <td>11000</td>
                    <td>12</td>
                    <td>aniezeoformic@gmail.com</td>
                    <td><button type="button" class="alter_product_btn edit_product_btn btn btn-primary" data-product-action="edit" data-product-id="62743bf145c490.38476519">EDIT</button></td>
                    <td><button type="button" class="alter_product_btn delete_product_btn btn btn-danger" data-product-action="delete" data-product-id="62743bf145c490.38476519">DELETE</button></td>
                </tr><script>
                           product_elements_edit = document.querySelectorAll('.alter_product_btn');
                           product_elements_edit_btn = document.querySelectorAll('.edit_product_btn');
                           product_id= null, action = null;
                          Array.prototype.forEach.call(product_elements_edit, elem => {
                            elem.addEventListener('click', (e) => {
                              console.log(elem.dataset);
                              product_id = elem.dataset.productId;
                              action = elem.dataset.productAction;
                            });
                          })
                          

                          $(document).ready(function() {
                          var searchcount = 10;
                          $(".delete_product_btn").click(function(event) {
                            // product_id = $(".edit_product_btn");
                              console.log(product_id);
                              
                              if(product_id !== null && action != null && confirm("Are you sure you want to proceed ?")){

                                $(".product_table_body").load("inc/productTable.inc.php", {
                                  product_id:product_id,
                                  action: action
                                });
                                
                              }
                              else{
                               
                              }
                          });
                         
                          $(".edit_product_btn").click(function(event) {
                            document.querySelector('#product_table').click();
                              
                              setTimeout(() => {
                                document.querySelector('#product_edit').click();

                              }, 500)
                            // product_id = $(".edit_product_btn");
                              
                              
                              if(product_id !== null && action != null){
                                
                                $("#edit_product_cnt").load("inc/edit-product.inc.php", {
                                  product_id:product_id,
                                  action: action
                                });
                                
                              }
                              else{
                                
                              }
                          });
                      });
                        </script>                        
                        
                    </tbody>
                    </table>
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
                Select a product above to be edited              </div>
            </div>
          </div>
        </div>
        </div>
      </div>
</section>