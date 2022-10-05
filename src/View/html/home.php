<?php
echo $header("home");
?>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3>Create a new Shop</h3>
                        </div>
                        <div class="card-body">
                            <form action="/shop/create" method="post">
                                <div class="form-group">
                                    <label for="name">shop name</label>
                                    <input type="text" name="shop_name" id="shop_name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="name">shop email</label>
                                    <input type="email" name="email" id="email" class="form-control">
                                </div>
                                <!-- shop description -->
                                <div class="form-group">
                                    <label for="description">shop description</label>
                                    <input type="description" name="description" id="description" class="form-control">  
                                </div>
                                <!-- shop type option -->
                                <!-- selcct field  -->
                                <div class="form-group">
                                    <label for="type">shop type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="clothing">clothing</option>
                                        <!-- <option value="phone">phone , laptop and accesories </option>
                                        <option value="food">food</option>
                                        <option value="cosmetics">cosmetics</option>
                                        <option value="furniture">furniture</option>
                                        <option value="automobile">automobile</option>

                                        <option value="others">others</option> -->
                                    </select>

                                
                                <div class="form-group">
                                    <button type="submit" name="signup" class="btn btn-primary">Create Shop</button>
                                </div>
                                
                                       
                                    
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- new section to shop list of shops -->
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3>Shop List</h3>
                        </div>
                    <?php
                    if (isset($shops) && count($shops) > 0) {
                        ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Shop Name</th>
                                    <th>Shop Description</th>
                                    <th>Shop Email</th>
                                    <th>Shop ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                        <?php foreach($shops as $shop): ?>
                                    <tr>
                                        <td><?= $shop['name'] ?></td>
                                        <td><?= $shop['unique_id'] ?></td>
                                        <td>  <a href="/shop/<?= $shop['public_unqiue_id'] ?>/add/product" class="btn btn-primary">Add Product</a></td>
                                        <td>  <a href="/shop/<?= $shop['public_unqiue_id'] ?>/products" class="btn btn-primary">View Products</a></td>
                                        <td>  <a href="/shop/<?= $shop['public_unqiue_id'] ?>/delete" class="btn btn-danger">Delete Shop</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                                       
                        <?php
                        } else {
                                    echo "<h2>No shops found</h2>";
                                }
                        ?>
                            
                        </div>
                    </div>
                </div>
            </div>
    </main>
</body>
</html>