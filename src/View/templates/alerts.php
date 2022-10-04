<div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                    if (isset($errors)) {
                        foreach ($errors as $error) {
                            echo $error !== null ?  "<div class='alert alert-danger' role='alert'>$error</div>" : false;
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                    if (isset($success)) {
                        foreach ($success as $data) {
                            echo "<div class='alert alert-success' role='alert'>$data</div>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                    if (isset($info)) {
                        foreach ($info as $info) {
                            echo "<div class='alert alert-info' role='alert'>$info</div>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                    if (isset($warning)) {
                        foreach ($warning as $warning) {
                            echo "<div class='alert alert-warning' role='alert'>$warning</div>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>