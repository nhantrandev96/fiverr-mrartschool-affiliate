<?php if($category) { ?>
	<section class="jumbotron text-center">
	    <div class="container">
			<div class="category-box">
				<?php 
					$image = $category['image'] != '' ? 'assets/images/product/upload/thumb/' . $category['image'] : 'assets/images/no_image_available.png';
				?>
				<img src="<?= resize($image,200,200) ?>">
				<div>
					<h1 class="jumbotron-heading"><?= $category['name'] ?></h1>
					<div class="text-left mb-0"><?= $category['description'] ?></div>
				</div>
			</div>
	    </div>
	</section>
<?php } else { ?>
	<br><br>
<?php } ?>

<div class="container">
    <div class="row">
        <div class="col-12 col-sm-3">
            <div class="card bg-light mb-3">
                <div class="card-header bg-primary text-white text-uppercase"><i class="fa fa-list"></i> Categories</div>

                <?php
                	function display_with_children($parentRow, $level = 0) { 
                		$space = $level > 0 ? str_repeat("&nbsp;&nbsp;&nbsp;", $level).'- ' : '';
                		foreach ($parentRow as $key => $row) {
						    echo '<li data-id="'. $row['id'] .'" class="'. ($row['children'] ? 'has-children' : '') .'" ><span>'. $space .'<a href="'. base_url('store/category/'. $row['slug']) .'">'. $row['name']."</a></span>"; 
						    if ($row['children']) {
						        echo '<ul>';display_with_children($row['children'], $level + 1);echo '</ul>';
						    }
						    echo '</li>';
                		}
					}

					echo '<ul class="category_block">';
					echo '<li data-id="0" ><span><a href="'. base_url('store/category/') .'">All Categories</a></span>'; 
					display_with_children($category_tree, 0);
					echo '</ul>';
                ?>

                <script type="text/javascript">
                	$(".category_block a").click(function(e){ e.stopPropagation(); });
                	$(".category_block .has-children").click(function(e){
                		e.stopPropagation();
                		$(this).find("> ul").slideToggle();
                	})

                	<?php if($category) { ?>
	                	var c = $('[data-id="<?= $category['id'] ?>"]').parents("li");
						var ele = c[c.length-1];
						$(ele).find("ul").show()
					<?php } ?>
                </script>
            </div>
        </div>
        <div class="col">
            <div class="row product-list">
				<?php if(!$products){ ?>
					<div class="col-sm-12">
						<h3 class="mb-4 mt-4 text-center text-muted">No Products found in this category</h3>
					</div>
				<?php } ?>
				<?php foreach ($products as $key => $product) { ?>
				<div class="col-md-4 col-sm-6 mb-2">
					<div class="card h-100">
						<?php $href = base_url("store/". base64_encode($user_id) . "/product/". $product['product_slug']); ?>
						<a href="<?php echo $href ?>"><img src="<?php echo resize('assets/images/product/upload/thumb/'. $product['product_featured_image'], 245,165); ?>" class="card-img-top" style="height:165px;"></a>
						<div class="card-body">
							<h4 class="card-title">
								<a href="<?php echo $href ?>"><?php echo $product['product_name'] ?></a>
							</h4>
							<h5><?php echo c_format($product['product_price']) ?></h5>
							<p class="card-text"><?php echo $product['product_short_description'] ?></p>
						</div>
						<div class="card-footer">
							<a href="<?php echo $href ?>" class="btn btn-primary btn-block"><?= __('store.buy_now') ?></a>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
        </div>

    </div>
</div>
