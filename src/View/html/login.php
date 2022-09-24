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
                            <h3>Login</h3>
                        </div>
                        <div class="card-body">
                            <form action="/login" method="post">
                                <div class="form-group">
                                    <label for="name">Username / Email</label>
                                    <input type="text" name="login" id="name" class="form-control">
                                </div>
                              
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" name="Login" class="btn btn-primary">Login</button>
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