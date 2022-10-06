<form class="row g-3" id="add_product" action="inc/add-product.inc.php" method="post" enctype="multipart/form-data">
          <div class="col-md-4" id="product_name_cnt">
            <label for="product_name" class="form-label">Product name</label>
            <input type="text" class="form-control  " name="product_name" id="product_name">
            <div class="invalid-feedback">
              This field is required
            </div>
          </div>
          <div class="col-md-4" id="product_size_cnt">
            <label for="product_size" class="form-label">Product Size</label>
            <input type="text" class="form-control" name="product_size" id="product_size">
            <div class="invalid-feedback">
              This field is required
            </div>
          </div>
          
          <div class="col-md-5" id="product_category_cnt">
            <label for="product_category" class="form-label">Category : use comma to separate</label>
            <input type="text" class="form-control" id="product_category" name="product_category">
            <div class="invalid-feedback">
              Please provide a valid city.
            </div>
          </div>
          <!-- <div class="col-md-3">
            <label for="validationServer04" class="form-label">State</label>
            <select class="form-select is-invalid" id="validationServer04" required="">
              <option selected="" disabled="" value="">Choose...</option>
              <option>...</option>
            </select>
            <div class="invalid-feedback">
              Please select a valid state.
            </div>
          </div> -->
          <div class="col-md-4" id="product_price_cnt">
            <label for="product_price" class="form-label">Price</label>
            <input type="Number" class="form-control" name="product_price" id="product_price" required="">
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
              <input type="radio" name="discount_method" value="price_cut" class="form-check-input" id="exampleRadio2">
              <label class="form-check-label" for="exampleRadio2">Price Cut</label>
            </div>
            <div class="col-md-3" id="product_discount_cnt">
            <label for="product_discount" class="form-label">Discount</label>
            <input type="text" class="form-control" id="product_discount" name="product_discount" value="0">
            <div class="invalid-feedback">
              Please provide a valid zip.
            </div>
            
          </div></fieldset>
          <div class="col-md-3" id="product_quantity_cnt">
            <label for="product_quantity" class="form-label">Quantity Added</label>
            <input type="number" class="form-control " id="product_quantity" required="" min="0" name="product_quantity">
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
        <fieldset class="col-mb-3">
            <legend>Gender</legend>
            <div class="form-check">
              <input type="radio" name="gender" value="male" class="form-check-input" id="exampleRadio1">
              <label class="form-check-label" for="exampleRadio1">Male</label>
            </div>
            <div class="mb-3 form-check">
              <input type="radio" name="gender" value="female" class="form-check-input" id="exampleRadio2">
              <label class="form-check-label" for="exampleRadio2">Female</label>
            </div>
            <div class="mb-3 form-check">
              <input type="radio" name="gender" value="unisex" class="form-check-input" id="exampleRadio2">
              <label class="form-check-label" for="exampleRadio2" selected="">unisex</label>
            </div>
          </fieldset>
          <div class="col-md-4">
            <label for="validationServerUsername" class="form-label">Added By</label>
            <div class="input-group">
              <span class="input-group-text" id="inputGroupPrepend3">@</span>
              <input type="text" class="form-control" id="validationServerUsername" value="<?=$_SESSION['username']?>" aria-describedby="inputGroupPrepend3" required="" readonly="">
              <div class="invalid-feedback">
                Please choose a username.
              </div>
            </div>
          </div>
          <!-- <div class="col-12">
            <div class="form-check">
              <input class="form-check-input is-invalid" type="checkbox" value="" id="invalidCheck3" required="">
              <label class="form-check-label" for="invalidCheck3">
                Agree to terms and conditions
              </label>
              <div class="invalid-feedback">
                You must agree before submitting.
              </div>
            </div>
          </div> -->
          <div class="col-12">
            <button class="btn btn-primary" name="product_add" type="submit">Submit form</button>
          </div>
        </form>