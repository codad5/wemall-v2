<!Doctype html>
<html>
<head>
    <title>Home</title>
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
                            <h3>Your Shop details</h3>
                        </div>
                        <div class="card-body">
                           <div class="alert alert-danger">
                                        Name : @data(name)
                                    </div>
                                    <div class="alert alert-success">
                                        Description : @data(description)
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>