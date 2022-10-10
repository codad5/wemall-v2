<?=$header([] , "Login")?>
<?=$notification()?>
<br>
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
                                
                                   
                            </form>
                            Don`t have an account <a href="/signup">Signup</a> now!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>