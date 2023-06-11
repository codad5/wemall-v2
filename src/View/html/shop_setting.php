
<?=$header(["shop" => $shop, $shop['name']])?>
<?php
$limited_access = $_SESSION['admin_level'] < 2;
?>
<section class="my-3" id="accordion">
    <div class="bd-heading sticky-xl-top align-self-start mt-5 mb-3 mt-xl-0 mb-xl-2" style="background:#fff;z-index:10;">
        <h3>Shop Settings</h3>
    </div>
    <div>
        <div class="bd-example">
            <div class="accordion" id="accordionExample">
                <!-- BEGIN OF SECON ACCORDITION ITEM {ADD PRODUCTS} -->
                <div class="accordion-item">
                    <h4 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" id="accordion_add_product_btn" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Add New Admin
                        </button>
                    </h4>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body">
                            <article class="my-3" id="validation">
                                <div class="bd-heading sticky-xl-top align-self-start mt-5 mb-3 mt-xl-0 mb-xl-2">
                                    <h3><?= $limited_access ? 'Dont have Access!' : "Add new Admin" ?></h3>
                                    <!-- <a class="d-flex align-items-center" href="../forms/validation/">Documentation</a> -->
                                </div>
                                <div>
                                    <?php if(!$limited_access): ?>
                                        <form action="admin/add" method="post" <?= $limited_access ? 'hidden' : '' ?>>
                                            <input name="email" type="text" placeholder="Email or Username">
                                            <select name="level" value="1">
                                                <option value="1" >Normal Admin</option>
                                                <option value="2" >Super Admin</option>
                                            </select>
                                            <button>
                                                Submit
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h4 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" id="accordion_add_product_btn" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            All Admins
                        </button>
                    </h4>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <?php
                                if(isset($admins) && count($admins) > 0):
                                    $count = 1;
                                    ?>
                                    <table class="table table-striped table-hover table-responsive">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Level</th>
                                            <th>Added by</th>
                                            <th>Remove</th>
                                        </tr>
                                        </thead>
                                        <tbody class="product_table_body">
                                        <form action="/shop/<?=$shop['shop_id']?>/admin/delete" method="post" id="delete_admin_form">
                                        <input type="hidden" name="user_id" data-user-id>
                                        <?php
                                        foreach($admins as $admin):
                                            $is_self = $admin['username'] == $_SESSION['username'];
                                            $cant_be_deleted = $limited_access || $is_self
                                        ?>
                                            <tr>
                                                <td><?=$count?></td>
                                                <td><?=$admin['name']?> <?= $is_self  ?  "(You)" : '' ?> </td>
                                                <td><?=$admin['username']?></td>
                                                <td><a href="mailto:<?=$admin['email']?>"> <?=$admin['email']?> </a></td>
                                                <td><?=$admin['level']?></td>
                                                <td><?=$admin['added_by_username'] == $admin['username'] ? 'Creator' : $admin['added_by_username']?></td>
                                                <td><button type="button"  class="alter_product_btn delete_product_btn btn <?=$cant_be_deleted ? 'btn-danger disabled' : 'btn-danger' ?>" <?=$cant_be_deleted ? 'disabled' : '' ?> readonly  data-user-id="<?=$admin['user_id']?>">DELETE</button></td>
                                            </tr>
                                        <?php
                                        endforeach;
                                        ?>
                                        </form>
                                        <!-- script to  load the edit form through ajax and delete the edit form -->
                                        <script>
                                            document.querySelectorAll("button[data-user-id]").forEach(button => {
                                                button.addEventListener('click', (e) => {
                                                    console.clear()
                                                    console.log('trying', e.target.dataset.userId)
                                                    document.querySelector('input[data-user-id]').value = e.target.dataset.userId
                                                    if(confirm("Are you sure you wanna delete This admin?")) document.querySelector('form#delete_admin_form').submit();
                                                })
                                            })
                                        </script>
                                        <!-- end of script to  load the edit form through ajax and delete the edit form -->


                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <h2>
                                        No Product yet!
                                    </h2>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h4 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" id="accordion_add_product_btn" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Create new App key
                        </button>
                    </h4>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body">
                            <article class="my-3" id="validation">
                                <div class="bd-heading sticky-xl-top align-self-start mt-5 mb-3 mt-xl-0 mb-xl-2">
                                    <h3><?= $limited_access ? 'Dont have Access!' : "Add new Admin" ?></h3>
                                    <!-- <a class="d-flex align-items-center" href="../forms/validation/">Documentation</a> -->
                                </div>
                                <div>
                                    <?php if(!$limited_access): ?>
                                    <form action="apikey/new" method="post" <?= $limited_access ? 'hidden' : '' ?>>
                                        <input name="app-name" id="app-name" type="text" placeholder="app name (test 1)">
                                        <select name="app-type" id="api-key-type">
                                            <option value="web" selected>
                                                Web app
                                            </option>
                                            <option value="app">
                                                Apps (mobile , desktop)
                                            </option>
                                        </select>
                                        <input name="domain-input" id="domain-input" type="text" placeholder="You web app domain (myapp.com)">
                                        <button>
                                            Submit
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h4 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" id="accordion_add_product_btn" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            All App Keys
                        </button>
                    </h4>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <?php
                                if(isset($api_keys) && count($api_keys) > 0):
                                    $count = 1;
                                    ?>
                                    <table class="table table-striped table-hover table-responsive">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>App Name</th>
                                            <th>Key</th>
                                            <th>Constraint</th>
                                            <th>platform</th>
                                            <th>Added by</th>
                                            <th>Remove</th>
                                        </tr>
                                        </thead>
                                        <tbody class="product_table_body">
                                        <form action="/shop/<?=$shop['shop_id']?>/admin/delete" method="post" id="delete_admin_form">
                                            <input type="hidden" name="user_id" data-user-id>
                                            <?php
                                            foreach($api_keys as $api_key):
                                                $is_self = $api_key['creator'] == $_SESSION['username'];
                                                ?>
                                                <tr>
                                                    <td><?=$count?></td>
                                                    <td><?=$api_key['app_name']?> <?= $is_self  ?  "(You)" : '' ?> </td>
                                                    <td><?=$api_key['app_key']?></td>
                                                    <td><?=$limited_access ? "***" : $api_key['app_constraint']?></td>
                                                    <td><?=$api_key['platform']?></td>
                                                    <td><?= $api_key['creator'].($is_self ? '(You)' : "")?></td>
                                                    <td><button type="button"  class="alter_product_btn delete_product_btn btn <?=$limited_access ? 'btn-danger disabled' : 'btn-danger' ?>" <?=$cant_be_deleted ? 'disabled' : '' ?> readonly >DELETE</button></td>
                                                </tr>
                                            <?php
                                            endforeach;
                                            ?>
                                        </form>
                                        <!-- script to  load the edit form through ajax and delete the edit form -->
                                        <script>
                                            document.querySelectorAll("button[data-user-id]").forEach(button => {
                                                button.addEventListener('click', (e) => {
                                                    console.clear()
                                                    console.log('trying', e.target.dataset.userId)
                                                    document.querySelector('input[data-user-id]').value = e.target.dataset.userId
                                                    if(confirm("Are you sure you wanna delete This admin?")) document.querySelector('form#delete_admin_form').submit();
                                                })
                                            })
                                        </script>
                                        <!-- end of script to  load the edit form through ajax and delete the edit form -->


                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <h2>
                                        No Key yet!
                                    </h2>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END OF FIRST ACCORDITION ITEM {ADD PRODUCT} -->
            </div>
        </div>
    </div>
</section>
<script>
    let apiKeySelect = document.querySelector("#api-key-type");
    let domainInput = document.querySelector("#domain-input")
    apiKeySelect.addEventListener('change', (e) => {
        switch (e.target.value) {
            case 'web':
                domainInput.hidden = false;
            break;
            case 'app':
                domainInput.hidden = true;
            break;
        }
    })
</script>
<?=$footer()?>