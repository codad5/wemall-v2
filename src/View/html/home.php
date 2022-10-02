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
                                
                                <div class="form-group">
                                    <button type="submit" name="signup" class="btn btn-primary">Create Shop</button>
                                </div>
                                
                                       
                                    
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>