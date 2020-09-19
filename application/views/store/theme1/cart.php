<div class="container">
    <div class="row">
        <div class="col-sm-12">
        	<?php if($products) { ?>
        		<br><h1><?= __('store.shopping_cart') ?></h1><br>
	        	<form method="POST" id="cart-form">
			        <div class="shopping-cart">
			        	<div class="column-labels">
			        		<label class="product-image"><?= __('store.image') ?></label>
			        		<label class="product-details"><?= __('store.product') ?></label>
			        		<label class="product-price"><?= __('store.price') ?></label>
			        		<label class="product-quantity"><?= __('store.quantity') ?></label>
			        		<label class="product-removal"><?= __('store.remove') ?></label>
			        		<label class="product-line-price"><?= __('store.total') ?></label>
			        	</div>
			        	<?php foreach ($products as $key => $product) { ?>
			        	<div class="product">
			        		<div class="product-image">
			        			<a class="thumbnail" href="<?= $product['link'] ?>"> 
	                            	<img class="media-object" src="<?= $product['product_featured_image'] ?>" > 
	                            </a>
			        		</div>
			        		<div class="product-details">
			        			<div class="product-title"><a  href="<?= $product['link'] ?>"> <?= $product['product_name'] ?></a></div>
			        			<p class="product-description"><?= $product['product_short_description'] ?></p>
			        		</div>
			        		<div class="product-price"><?= c_format($product['product_price']) ?></div>
			        		<div class="product-quantity">
								<?php if($product['product_type'] != 'downloadable'){ ?>
									<div class="number-input mini-number-input">
					                    <input type="text" name="quantity[<?= $product['cart_id'] ?>]" value="<?= $product['quantity'] ?>" size="1" class="form-control qty-input">
					                    <div>
					                        <span class='plus'> + </span>
					                        <span class='minus'> - </span>
					                    </div>
					                </div>
					            <?php } else { ?>
					            	<?= $product['quantity'] ?>
					            <?php } ?>
			        		</div>
			        		<div class="product-removal">
			        			<a class="btn btn-danger" href="<?= $cart_url."?remove=".$product['cart_id'] ?>"><span class="fa fa-trash"></span> <span class="hide-mobile"><?= __('store.remove') ?></span></a>
			        		</div>
			        		<div class="product-line-price"><?= c_format($product['total']) ?></div>
			        	</div>
			        	<?php } ?>

			        	<div class="totals">
			        		<div class="totals-item">
			        			<label><?= __('store.subtotal') ?></label>
			        			<div class="totals-value" id="cart-subtotal"><?= c_format($sub_total) ?></div>
			        		</div>
			        		<div class="totals-item totals-item-total">
			        			<label><?= __('store.grand_total') ?></label>
			        			<div class="totals-value" id="cart-total"><?= c_format($total) ?></div>
			        		</div>
			        	</div>

			        </div>
			        <br>
		        	<div class="row">
		        		<div class="col-sm-6 text-left">
		        		</div>	
		        		<div class="col-sm-6 text-right">
		        			<a class="btn btn-default" href="<?= $base_url ?>"><?= __('store.continue_shopping') ?></a>
		        			<a class="btn btn-success" href="<?= $base_url ?>checkout"><?= __('store.checkout') ?></a>
		        		</div>	
		        	</div>
	            </form>
	        <?php } else { ?>
	        	<div class="text-center">
	        		<br><br><br><br>
		        	<h4><?= __('store.shopping_cart_is_empty') ?></h4>

		        	<img src="<?= base_url('assets/plugins/store/img/empty_cart_teaser.jpg') ?>">
		        	<br><br><br>
		        	<a class="btn btn-primary" href="<?= $base_url ?>"><?= __('store.continue_shopping') ?></a>
	        	</div>
	        <?php } ?>
        </div>
    </div>
</div>

<script type="text/javascript">
	var xhr ;

	$("#cart-form").delegate(".qty-input","change",function(){
		if(xhr && xhr.readyState != 4) xhr.abort();

		$this = $(this);
		xhr = $.ajax({
			url:'',
			type:'POST',
			dataType:'html',
			data:$("#cart-form").serialize(),
			beforeSend:function(){},
			complete:function(){},
			success:function(html){
				$('.shopping-cart').html($(html).find(".shopping-cart").html());
			},
		})
		return false;
	})
</script>