
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
          <form class="row g-3" id="add_product" action="/shop/<?=$shop['public_unqiue_id']?>/product/create" method="post" enctype="multipart/form-data">
          <div class="col-md-4" id="product_name_cnt">
            <label for="product_name" class="form-label">Product name</label>
            <input type="text" class="form-control  " name="name" id="product_name">
            <div class="invalid-feedback">
              This field is required
            </div>
          </div>
          <!-- product description -->
          <div class="col-md-4" id="product_description_cnt">
            <label for="product_description" class="form-label">Product Description</label>
            <textarea class="form-control" name="description" id="product_description" rows="3"></textarea>
            <div class="invalid-feedback">
              This field is required
            </div>
          <div class="col-md-4" id="product_price_cnt">
            <label for="product_price" class="form-label">Price</label>
            <input type="Number" class="form-control" name="price" id="product_price" required="">
            <div class="invalid-feedback">
              Invalid Price
            </div>
          </div>
           <fieldset class="col-mb-3">
            <legend>Discount Methods</legend>
            <div class="col-mb-3 form-check">
              <input type="radio" name="discount_method" value="percentage" class="form-check-input" id="exampleRadio1">
              <label class="form-check-label" for="exampleRadio1">Percentage</label>
            </div>
            <div class="col-mb-3 form-check">
              <input type="radio" name="discount_method" value="flat" class="form-check-input" id="exampleRadio2">
              <label class="form-check-label" for="exampleRadio2">Price Cut</label>
            </div>
            <div class="col-md-3" id="product_discount_cnt">
            <label for="product_discount" class="form-label">Discount</label>
            <input type="text" class="form-control" id="product_discount" name="product_discount" value="0">
            <div class="invalid-feedback">
              Please provide a valid zip.
            </div>
            
          </div></fieldset>
          
          <!-- <div class="col-md-5" id="product_category_cnt">
            <label for="product_category" class="form-label">Category : use comma to separate</label>
            <input type="text" class="form-control" id="product_category" name="product_category">
            <div class="invalid-feedback">
              Please provide a valid city.
            </div>
          </div> -->
          <?=$shop['form']?>
        <div class="col-md-3" id="product_quantity_cnt">
            <label for="product_quantity" class="form-label">Quantity Added</label>
            <input type="number" class="form-control " id="product_quantity" required="" min="0" name="quantity">
            <div class="invalid-feedback">
              Please provide a valid zip.
            </div>
          </div>
          <div class="col-mb-3">
              <input type="file" class="form-control" id="product_image" name="product_image[]" multiple="" maxlength="5" aria-label="Large file input example">
                <div class="invalid-feedback">
                Max of 5 images
                </div>
        </div>
          <div class="col-md-4">
            <label for="validationServerUsername" class="form-label">Added By</label>
            <div class="input-group">
              <span class="input-group-text" id="inputGroupPrepend3">@</span>
              <input type="text" class="form-control" id="validationServerUsername" name="created_by" value="<?=$_SESSION['username']?>" aria-describedby="inputGroupPrepend3" required="" readonly="">
              <div class="invalid-feedback">
                Please choose a username.
              </div>
            </div>
          </div>
          <div class="col-12">
            <button class="btn btn-primary" name="product_add" type="submit">Submit form</button>
          </div>
        </form>
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