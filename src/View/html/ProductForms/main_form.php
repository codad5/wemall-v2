<?php
  $values = $values ?? [];
  $values = json_encode($values);
  $values = json_decode($values);
?>
<form class="row g-3" id="add_product" action="/shop/<?=$shop['shop_id']?>/product/<?=$values->form_action ?? "create"?>" method="post" enctype="multipart/form-data">
  <div class="col-md-4" id="product_name_cnt">
    <label for="product_name" class="form-label">Product name</label>
      <input type="text" class="form-control  " name="name" id="product_name" value="<?=$values?->name ?? '' ?>">
      <div class="invalid-feedback">
        This field is required
      </div>
  </div>
  <!-- product description -->
  <div class="col-md-4" id="product_description_cnt">
    <label for="product_description" class="form-label">Product Description</label>
    <textarea class="form-control" name="description" id="product_description" rows="3" value=""><?=$values->description ?? '' ?>
    </textarea>
    <div class="invalid-feedback">
      This field is required
    </div>
  </div>
  <!-- product category -->
  <div class="col-md-4" id="product_category_cnt">
    <label for="product_category" class="form-label">Product Category</label>
    <input type="hidden" class="form-control  " value="<?=$values?->category ?? 'home' ?> " name="category" id="product_category">
    <div class="invalid-feedback">
      This field is required
    </div>
  </div>
  <!-- product price -->
  <div class="col-md-4" id="product_price_cnt">
    <label for="product_price" class="form-label">Price</label>
    <input type="Number" class="form-control" name="price" id="product_price" value="<?=$values?->price ?? '' ?>" required="">
    <div class="invalid-feedback">
      Invalid Price
    </div>
  </div>
  <!-- discount method -->
  <div class="col-md-4" id="discount_method_cnt">
    <label for="discount_method" class="form-label">Discount Method</label>
    <select class="form-select" name="discount_type" id="discount_method" value="<?=$values?->discount_type ?? '' ?>">
      <option value="cut">Flat</option>
      <option value="percentage">Percentage</option>
    </select>
    <div class="invalid-feedback">
      This field is required
    </div>
  </div>
  <!-- product discount -->
  <div class="col-md-4" id="product_discount_cnt">
    <label for="product_discount" class="form-label">Discount</label>
    <input type="Number" class="form-control" name="discount" id="product_discount" required="" value="<?=$values?->discount ?? '' ?>">
    <div class="invalid-feedback">
      Invalid Discount
    </div>
  </div>
  <!-- <fieldset class="col-mb-3" >
    <legend>Discount Methods</legend>
    <div class="col-mb-3 form-check">
      <input type="radio" name="discount_type" value="percentage" class="form-check-input" id="exampleRadio1">
      <label class="form-check-label" for="exampleRadio1">Percentage</label>
    </div>
    <div class="col-mb-3 form-check">
      <input type="radio" name="discount_type" value="flat" class="form-check-input" id="exampleRadio2">
      <label class="form-check-label" for="exampleRadio2">Price Cut</label>
    </div>
    <div class="col-md-3" id="product_discount_cnt">
      <label for="product_discount" class="form-label">Discount</label>
      <input type="text" class="form-control" id="product_discount" name="discount" value="0">
      <div class="invalid-feedback">
        Invalid Discount
      </div>
    </div>
  </fieldset> -->

  <!-- SHOP FROM -->
  <?=$shop['form']($shop['type'], $values)?>
  <!-- END OF SHOP FORM -->
  <div class="col-md-3" id="product_quantity_cnt">
    <label for="product_quantity" class="form-label">Quantity <?=isset($values?->form_action) ? 'Left' : 'To be added' ?></label>
      <input type="number" class="form-control " value="<?=isset($values?->quantity) ? $values?->quantity - $values?->sold : '' ?>" id="product_quantity" required="" min="0" name="quantity">
    <div class="invalid-feedback">
      Error Here
    </div>
  </div>
  <?php
    if(!isset($values?->form_action)){
  ?>
  <div class="col-mb-3">
    <input type="file" class="form-control" id="product_image" name="images[]" multiple="" maxlength="5" aria-label="Large file input example">
    <div class="invalid-feedback">
    Max of 5 images
    </div>
  </div>
  <?php
    }
  ?>
  <div class="col-md-4">
    <label for="validationServerUsername" class="form-label">Added By</label>
    <div class="input-group">
      <span class="input-group-text" id="inputGroupPrepend3">@</span>
      <input type="text" class="form-control" id="validationServerUsername" name="created_by" value="<?=$values?->creator ?? $_SESSION['username']?>" aria-describedby="inputGroupPrepend3" required="" readonly="">
      <div class="invalid-feedback">
        Please choose a username.
      </div>
    </div>
  </div>
  <div class="col-12">
    <button class="btn btn-primary" name="product_add" type="submit">Submit form</button>
  </div>
</form>
<script>
  // script to check if number of file in input is greater than 5
  var input = document.getElementById('product_image');
  input.addEventListener('change', function(e) {
    if (input.files.length > 5) {
      input.setCustomValidity("Max of 5 images");
      // add is invalid class to input
      input.classList.add('is-invalid');
    } else {
      input.setCustomValidity("");
      // remove is invalid class to input
    }
  });
  //script to validate discount price based on discount type
  var discount_method = document.getElementById('discount_method');
  var product_discount = document.getElementById('product_discount');
  var product_price = document.getElementById('product_price');
  product_discount.addEventListener('input', function(e) {
    let invalid_cnt = document.getElementById('product_discount_cnt').querySelector('.invalid-feedback');
    if (discount_method.value == 'cut') {
      if (parseInt(product_discount.value) > parseInt(product_price.value)) {
        product_discount.setCustomValidity("Discount cannot be greater than price");
        // add is invalid class to input
        invalid_cnt.innerHTML = "Discount cannot be greater than price";
        product_discount.classList.add('is-invalid');
      } else {
        product_discount.setCustomValidity("");
        // remove is invalid class to input
        invalid_cnt.innerHTML = "Invalid Discount";
        product_discount.classList.remove('is-invalid');

      }
    } else {
      if (product_discount.value > 100) {
        product_discount.setCustomValidity("Discount cannot be greater than 100%");
        // add is invalid class to input
        invalid_cnt.innerHTML = "Discount cannot be greater than 100%";
        product_discount.classList.add('is-invalid');
      } else {
        product_discount.setCustomValidity("");
        // remove is invalid class to input
        invalid_cnt.innerHTML = "Invalid Discount";
        product_discount.classList.remove('is-invalid');
      }
    }
  });
  
</script>