<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <script src="<?php echo base_url('assets/plugins/store/lightgallery-all.min.js') ?>"></script>
            <script src="<?php echo base_url('assets/plugins/store/jquery.star-rating-svg.js') ?>"></script>
            <link href="<?php echo base_url('assets/plugins/store/lightgallery.css') ?>?v=<?= av() ?>" rel="stylesheet">
            <?php 
                $product_featured_image = $product['product_featured_image'] != '' ? $product['product_featured_image'] : '' ; 
                $_product_featured_image = base_url('assets/images/product/upload/thumb/'. $product_featured_image);
                $allimages = $this->Product_model->getAllImages($product['product_id']);
                $allvideo = $this->Product_model->getAllVideos($product['product_id']);
                $product_featured_image =  resize('assets/images/product/upload/thumb/'. $product['product_featured_image'], 500,500);
            ?>
            <br><br><h3 class="card-title"><?php echo $product['product_name'] ?></h3>
            <div class="row">
                <div class="col-sm-8">
                    <div class="thumbnails row" id="lightgallery">
                        <div class="col-sm-12">
                            <div data-src="<?php echo  $_product_featured_image; ?>" class="item">
                                <a class="thumbnail main-image" >
                                    <img src="<?php echo  $product_featured_image; ?>" itemprop="image" />
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <?php foreach($allimages as $images) { 
                                $img = resize('assets/images/product/upload/thumb/' .$images['product_media_upload_path'],100,100);
                                $bigImg = base_url('assets/images/product/upload/'.$images['product_media_upload_path']);
                                ?>
                                <div data-src="<?php echo  $bigImg; ?>" class="image-additional item"><a class="thumbnail" ><img src="<?php echo  $img; ?>" itemprop="image" /></a></div>
                            <?php } ?>
                            <?php foreach($allvideo as $videos) { 
                                $img = resize('assets/images/product/upload/thumb/' .$videos['product_media_upload_video_image'],100,100);
                                $youtube = $videos['product_media_upload_path'];
                                ?>
                                <div data-src="<?php echo $youtube; ?>" data-poster="" class="image-additional item"><a class="thumbnail" ><img src="<?php echo  $img; ?>" itemprop="image" /></a></div>
                             <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card-body">
                        
                        <ul class="list-unstyled">
                            <li><b><?= __('store.sku') ?> </b>: <?php echo $product['product_sku'];?></li>
                            <li><b><?= __('store.promoted_by') ?> </b>: <?php echo $user['username'];?></li>
                        </ul>
                        <p class="card-text"><?php echo $product['product_short_description'] ?></p>
                        <h4><?= __('store.price') ?> : <?php echo c_format($product['product_price']) ?></h4>
                        <br>
                        
                        <div class="cable_choosec">
                            <div class="product-price">
                                <div class="add-cart">
                                    <?php if($product['product_type'] != 'downloadable'){ ?>
                                        <div>
                                            <label class="control-label"><?= __('store.quantity') ?></label>
                                            <div class="number-input">
                                                <input type="text" min="1"  name="quantity" value="1" class="form-control quantity">
                                                <div>
                                                    <span class='plus'> + </span>
                                                    <span class='minus'> - </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <br>
                                    <button class="btn btn-info btn-block btn-cart"><?= __('store.add_to_cart') ?></button>
                                </div>
                            </div>
                           
                           <?php 
                                if($categories){ 
                                    echo "<div class='product-category'>";
                                    foreach ($categories as $key => $value) {
                                        echo "<a href='". base_url('store/category/'. $value['slug']) ."'>". $value['name'] ."</a>";
                                    }
                                    echo "</div>";
                                }
                            ?>

                            <div class="apply-coupon">
                                <label class="control-label"><?= __('store.enter_coupon_code') ?></label>
                                <input class="form-control coupon-code" name="coupon" value="" >
                                <div class="coupon-msg"></div>
                                <button class="btn btn-info btn-block btn-apply-coupon"><?= __('store.apply_coupon_code') ?></button>
                            </div>
                        </div>
                        
                        <span>
                            <span class="text-warning avg_rating"></span> <?= __('store.base_on') ?>
                            <?php echo count($ratings); ?>  <?= __('store.customer_reviews') ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card card-outline-secondary my-4">
                <div class="card-body">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#product_description"><?= __('store.description') ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#reviews"><?= __('store.reviews') ?></a>
                        </li>
                    </ul>
                    <hr>
                    <div class="tab-content">
                        <div class="tab-pane container active" id="product_description">
                            <?php echo $product['product_description'] ?>
                        </div>
                        <div class="tab-pane container fade" id="reviews">
                            <?php if(!empty($ratings)) { ?>
                            <?php foreach($ratings as $rating) { ?>
                            <div class="blockquote review-item row">
                                <div class="col-md-2 col-sm-2 text-center">
                                    <?php if(!empty($userdetails['avatar'])) { ?>
                                    <img class="rounded-circle reviewer" src="<?php echo base_url(); ?>assets/images/users/thumb/<?php echo $userdetails['avatar'];?>" width="70px">
                                    <?php } else { ?>
                                    <img class="rounded-circle reviewer" src="<?php echo base_url(); ?>assets/vertical/assets/images/users/avatar-1.jpg" alt="user" width="70px">
                                    <?php } ?>
                                </div>
                                <div class="col-md-10 col-sm-10">
                                    <div class="block-text rel zmin">
                                        <a href="" class="review-title"><?php echo $product['product_name'];?> <span><?php echo $rating['rating_created'];?></span></a>
                                        <div class="">
                                            <span class="rating-input" data-star='<?php echo (float)$rating['rating_number'] ?>'></span>
                                        </div>
                                        <p class="text-15"><?php echo $rating['rating_comments'];?></p>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <?php } else { ?>
                            <p class="text-left text-muted no-review"><?= __('store.there_are_no_reviews_for_this_product') ?></p>
                            <?php } ?>
                            <?php if($allowReview){ ?>
                                <hr>
                                <div class="">
                                    <div class="clearfix"></div>
                                    <h2 class="write-review"><?= __('store.write_a_review') ?></h2><br>
                                    <div id="createRatting" class="create_Rating">
                                        <input name="user_id" id="user_id" type="hidden" value="<?php echo !empty($session) ? $session['id'] : '';?>" />
                                        <input name="product_id" value="<?php echo $product['product_id'];?>" id="product_id" type="hidden" />
                                        <div class="form-group">
                                            <label class="control-label"><?= __('store.your_review') ?></label>
                                            <textarea name="comment" id="comment" placeholder="<?= __('store.your_review') ?>" cols="80" class="form-control"></textarea>
                                            <div class="help-block"><span class="text-danger"><?= __('store.note') ?></span> <?= __('store.html_is_not_translated') ?></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"><?= __('store.email') ?></label>
                                            <input name="email" id="post_email" placeholder="<?= __('store.enter_your_email') ?>" type="text" value="" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"><?= __('store.rating') ?></label>
                                            <div class="clearfix"></div>
                                            <div class="give-rating"></div>
                                            <input name="rating" value="0" id="rating_star" type="hidden" />
                                        </div>
                                        <button class="btn btn-success" name="submit" id="submit" onclick="processRating()"> <?= __('store.leave_a_review') ?> </button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cart-confirm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h5><?php echo $product['product_name'];?></h5>
                <p><?= __('store.has_beent_added_to_your_cart') ?></p>
                <hr>
                <a href="<?= $base_url ?>checkout" class="btn btn-primary mb-2"><?= __('store.procceed_to_checkout') ?></a>
                <button type="button" class="btn btn-default mb-2" data-dismiss="modal"><?= __('store.continue_shopping') ?></button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(".btn-apply-coupon").on('click',function(){
        var coupon_code = $(".apply-coupon .coupon-code").val();
        var product_id = '<?= $product['product_id'] ?>';
        $this = $(this);
        $.ajax({
            url:'<?= $add_coupon_url ?>',
            type:'POST',
            dataType:'json',
            data:{
                product_id:product_id,
                coupon_code:coupon_code,
            },
            beforeSend:function(){$this.btn("loading");},
            complete:function(){$this.btn("reset");},
            success:function(json){
                $(".coupon-msg").html('');
                
                if(json['success']){
                    $(".coupon-msg").html("<div class='alert alert-success'>"+ json['success'] +"</div>");
                }
                if(json['error']){
                    $(".coupon-msg").html("<div class='alert alert-danger'>"+ json['error'] +"</div>");
                }
            },
        })
    })
    $(".btn-cart").on('click',function(){
        var quantity = $(".add-cart .quantity").val();
        var product_id = '<?= $product['product_id'] ?>';
        $this = $(this);
        $.ajax({
            url:'<?= $add_tocart_url ?>',
            type:'POST',
            dataType:'json',
            data:{
                quantity:quantity,
                product_id:product_id,
            },
            beforeSend:function(){$this.btn("loading");},
            complete:function(){$this.btn("reset");},
            success:function(json){
                if(json['location']){
                    updateCart();
                    //window.location = json['location'];
                    $("#cart-confirm").modal("show");
                }
            },
        })
    })
    $('#lightgallery').lightGallery({
        download: false,
        controls:false,
        hash:false,
        actualSize:false,
        share:false,
        selector:'.item'
    });
    $(".rating-input").each(function(){
        var v = $(this).attr("data-star");
        $(this).starRating({
            starSize: 15,
            initialRating:v,
            readOnly:true,
            disableAfterRate:false,
        });
    })
    $('.give-rating').starRating({
        initialRating:0,
        starSize: 20,
        readOnly:false,
        disableAfterRate:false,
        callback: function(currentRating, $el){
            $("#rating_star").val(currentRating);
        }
    }); 
    $('.avg_rating').starRating({
        initialRating:'<?php echo $avg_rating ?>',
        starSize: 20,
        readOnly:true,
        disableAfterRate:false,
    }); 
    function processRating(){
        if($('#name').length > 0){ var name = $('#name').val(); } else { var name = ''; }
        var email = $('#post_email').val();
        var rating_star = $('#rating_star').val();
        var product_id = $('#product_id').val();
        var user_id = $('#user_id').val();
        var comment = $('#comment').val();
        if(comment != '' && rating_star != 0){
            $("#submit").prop("disabled",true);
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url();?>product/rating',
                data: 'product_id='+product_id+'&user_id='+user_id+'&comment='+comment+'&name='+name+'&email='+email+'&number='+rating_star,
                success : function(data) {
                    window.location.reload();
                    $("#submit").prop("disabled",false);
                }
            });
        } else {
            alert('<?= __('store.please_write_some_comment') ?>');
        }
    }
</script>