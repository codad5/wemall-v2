<!Doctype html>
<html>
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- tailwind cssc cdn -->
    <link href="https://unpkg.com/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <!-- tailwind js -->

</head>
<body>
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
                                
                                    <div class="alert alert-danger">
                                        @data(error)
                                    </div>
                                    <div class="alert alert-success">
                                        @data(success)
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