<?php if (isset($header) && isset($footer) && isset($shop)) : ?>
<?=$header(["shop" => $shop, $shop['name']])?>
        
<?=$footer()?>
<?php endif; ?>
