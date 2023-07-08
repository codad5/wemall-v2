
<?php if (isset($header) && isset($footer) && isset($notification)) : ?>
<?=$header([] , "Login")?>
<?=$notification()?>
<style>
    .main-section {
        width: 100vw;
        height: 100vh;
        display: grid;
        place-items: center;
    }
</style>
<section class="container main-section">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Coming Soon</h1>
            <p>This website is under construction. Please check back soon.</p>
        </div>
    </div>
</section>
<?php endif; ?>

