<!-- TAB SPACES -->
      <!-- CLOTHING product size -->
      <div class="col-md-4" id="product_size_cnt">
        <label for="product_size" class="form-label">Product Size</label>
        <input type="text" class="form-control" name="size" id="product_size" value="<?=$values?->size ?? '' ?>">
        <div class="invalid-feedback">
          This field is required
        </div>
      </div>
      <!-- product gender type -->
      <div class="col-md-4" id="gender_type">
        <label for="discount_method" class="form-label">Gender</label>
        <select class="form-select" name="gender" id="gender" value="<?=$values?->gender ?? '' ?>">
          <option value="male">Male</option>
          <option value="female">female</option>
          <option value="unisex">unisex</option>
        </select>
        <div class="invalid-feedback">
          This field is required
        </div>
      </div>
      <!-- <fieldset class="col-mb-3">
        <legend>Gender</legend>
          <div class="form-check">
            <input type="radio" name="gender" value="male" class="form-check-input" id="exampleRadio1" <?=isset($values) || $values?->gender == "female" ? 'checked' : false ?>>
              <label class="form-check-label" for="exampleRadio1">Male</label>
          </div>
          <div class="mb-3 form-check">
            <input type="radio" name="gender" value="female" class="form-check-input" id="exampleRadio2" <?=isset($values) || $values?->gender == "female" ? 'checked' : false ?>>
            <label class="form-check-label" for="exampleRadio2">Female</label>
          </div>
          <div class="mb-3 form-check">
            <input type="radio" name="gender" value="unisex" class="form-check-input" id="exampleRadio2" <?=isset($values) || $values?->gender == "female" ? 'checked' : false ?>>
            <label class="form-check-label" for="exampleRadio2" selected="">unisex</label>
          </div>
      </fieldset> -->
      <!-- product color -->
      <div class="col-md-4" id="product_color_cnt">
        <label for="product_color" class="form-label">Product Color</label>
          <input type="color" class="form-control" name="color" id="product_color" value="<?=$values?->color ?? '' ?>">
          <div class="invalid-feedback">
            This field is required
          </div>
      </div>


