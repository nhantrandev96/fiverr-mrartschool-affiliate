	<div class="page-content-wrapper ">
		
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="card m-b-30">
						<div class="card-body">
								
							
								<p class="text-muted m-b-30 font-14">* copy and paste the following code onto your web page or email for promotion</p>
								
								<pre class=" language-markup">
									<code class=" language-markup">
&lt;a href="<?php echo base_url();?>product/<?php echo $getProduct['product_slug'];?>/<?php echo $user_id;?>" &gt;
<?php if(!empty($getProduct['product_name'])) { ?> &lt;h3&gt;<?php echo $getProduct['product_name'];?>&lt;/h3&gt; <?php } ?>
<?php if(!empty($getProduct['product_featured_image'])) { ?>
&lt;img src="<?php echo base_url();?>/assets/images/product/featured/<?php echo $getProduct['product_featured_image'];?>" width="200" height="200" border="0" class="img-responsive"  /&gt;
<?php } ?>
&lt;/a&gt;
&lt;img src="<?php echo base_url();?>product/views/<?php echo $getProduct['product_slug'];?>/<?php echo $user_id;?>" border="0" width="1" height="1" /&gt;
									</code>
								</pre>
						</div> 
					</div> 
				</div> 
			</div> 
		</div> 
	</div> 
		