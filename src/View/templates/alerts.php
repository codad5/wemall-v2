<style>
    .my-alerts{
        position: fixed;
        top: 10;
        left: 0;
        width: 100%;
        z-index: 9999;
    }
</style>
<div class="my-alerts">
    <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php
                        if (isset($errors)) {
                            foreach ($errors as $error) {
                                // echo $error !== null ?  "<div class='alert alert-danger' role='alert'>$error</div>" : false;
                                ?>
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>Error ❌</strong> <?=$error?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php 

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
                                // echo "<div class='alert alert-success' role='alert'>$data</div>";
                                ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Hurry! 🤑</strong> <?=$data?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php 
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
                                // echo "<div class='alert alert-info' role='alert'>$info</div>";
                                ?>
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <strong>Notice ❗</strong> <?=$info?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php
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
                                // echo "<div class='alert alert-warning' role='alert'>$warning</div>";
                                ?>
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <strong>Warn ⚠</strong> <?=$warning?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
</div>
<!-- bootstrap auto remove alert -->
<script>
    $(document).ready(function(){
        $('.alert').delay(5000).fadeOut();
    });
</script>