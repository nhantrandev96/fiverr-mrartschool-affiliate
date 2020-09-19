<?php if($products) { ?>

<?php foreach ($products as $key => $product) { ?>

<li>

    <span class="item">

        <span class="item-left">

            <a class="thumbnail pull-left" href="<?= $product['link'] ?>"> 

                <img class="media-object" src="<?= $product['product_featured_image'] ?>"> 

            </a>

            <span class="item-info">

                <span class="item-name"><?= $product['product_name'] ?></span>

                <span>Quantity : <?= $product['quantity'] ?></span>

                <span>Price : <?= c_format($product['product_price']) ?></span>

            </span>

        </span>

        <span class="item-right">

            <button type="button" class="btn btn-xs btn-danger btn-remove-cart pull-right" data-href="<?= $base_url."cart/?checkout_page=true&remove=".$product['cart_id'] ?>"><i class="fa fa-close"></i></button>

        </span>

    </span>

    <div class="clearfix"></div>

</li>

<?php } ?>

<li>

    <hr>

    <b>Subtotal:</b> <?= c_format($sub_total) ?>

</li>

<li>

    <b>Total:</b> <?= c_format($total) ?>

</li>

<li class="divider"><hr></li>

<li><a class="btn btn-info btn-block" href="<?php echo base_url('store/cart') ?>">View Cart</a></li>

<?php } else { ?>
    <div class="text-center">
        <img src="<?= base_url('assets/plugins/store/img/empty_cart_teaser.jpg') ?>">
        <p>Shopping cart is empty</p>
    </div>
<?php } ?>