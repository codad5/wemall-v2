<?php if (isset($header) && isset($footer) && isset($notification)) : ?>
<?=$header([] , "Signup")?>
<?=$notification()?>
<br/>
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3>Signup</h3>
                        </div>
                        <div class="card-body">
                            <form action="/signup" method="post">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="name">Username</label>
                                    <input type="text" name="username" id="name" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="signup" class="btn btn-primary">Signup</button>
                                </div>
                                
                                    
                            </form>
                            Already have an account <a href="/login">Login</a> now!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
<?php endif; ?>