
<?=$header(["shop" => $shop, $shop['name']])?>
<section class="my-3" id="accordion">
    <div class="bd-heading sticky-xl-top align-self-start mt-5 mb-3 mt-xl-0 mb-xl-2" style="background:#fff;z-index:10;">
        <h3>Products Settings</h3>
        <a class="d-flex align-items-center" href="">Products Settings</a>
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
                                    <h3>Add New Admin</h3>
                                    <!-- <a class="d-flex align-items-center" href="../forms/validation/">Documentation</a> -->
                                </div>
                                <div>
                                    <form action="admin/add" method="post">
                                        <input name="email" type="email">
                                        <button>
                                            Submit
                                        </button>
                                    </form>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
                <!-- END OF FIRST ACCORDITION ITEM {ADD PRODUCT} -->
            </div>
        </div>
    </div>
</section>
<?=$footer()?>