<?php if(isset($shop)): ?>
<style>
    nav{
        height: 60px;

    }
    aside{
        position: absolute;
        top: 60px;
        left: 0;
        height: calc(100vh - 60px);
        display:inline-block;
        width:20vw;
        /* float: left; */
        background-color: #fff;
    }
    main{
        position: relative;
        top: 0;
        left: 20vw;
        min-height: calc(100vh - 60px);
        height: max-content;
        width:80vw;
        background-color: #fff;
        padding: 20px;
        overflow-y: scroll;
    }
    /* customize main scroll and reduce width of scroll bar */
    main::-webkit-scrollbar {
        width: 10px;
    }
    
   
</style>
<!-- bootstrap side panel to manage shop with icon -->
<aside class="" style="">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Shop Management</h4>
        </div>
        <!-- a mini profile of the shop -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <img src="data:image/jpeg;base64,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" alt="<?=$shop['name']?>" class="img-fluid">
                </div>
                <div class="col-md-8">
                    <h5><?=$shop['name']?></h5>
                    <p><?=$shop['description']?></p>
                </div>
            </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="/shop/<?=$shop['shop_id']?>">
                        <i class="fa fa-home"></i> Shop Home
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/shop/<?=$shop['shop_id']?>/product">
                        <i class="fa fa-shopping-cart"></i> Products
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/shop/<?=$shop['shop_id']?>/orders">
                        <i class="fa fa-shopping-bag"></i> Orders
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/shop/<?=$shop['shop_id']?>/settings">
                        <i class="fa fa-edit"></i> Shop Settings
                    </a>
                </li>
                <li class="list-group-item">
                    <a href="/shop/<?=$shop['shop_id']?>/delete">
                        <i class="fa fa-trash"></i> Delete Shop
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
</aside>

<!-- styling to make the main panel width shorter -->
<style>
    main {
        width: 80%;
    }
</style>

<?php endif; ?>