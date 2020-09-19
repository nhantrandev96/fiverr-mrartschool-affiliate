<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title m-0 text-center d-block w-100 ">
                <?= $name ?> Integration on website 
            </h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <?php 
            function ___h($text,$lan){
                $text = implode("\n", $text);
                $text = htmlentities($text);
                $text = '<div class="lang-copy" ><div class="copy">copy</div><pre class="language-'.$lan.'"><code class="language-'.$lan.'">'.$text.'</code></pre></div>';
                return $text;
            }

            $base_url  = base_url();
        ?>

        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/integration/prism/css.css') ?>?v=<?= av() ?>">
        <script type="text/javascript" src="<?= base_url('assets/integration/prism/js.js') ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/integration/prism/clipboard.min.js') ?>"></script>
        
        <div class="modal-body m-0 p-0">
            <div class="modal-ins">
                <div class="modal-ins-head">
                    <div class="row auto-fill-filed">
                        <div class="col-sm-6">
                            <label class="control-label">You can edit your website link in this box and it auto change in the code box</label>
                            <input type="text" name="WebsiteUrl" data-default='WebsiteUrl' class="form-control" placeholder="Enter Website Url" value="<?= $target_link ?>">
                        </div>
                    </div>
                </div>
                
                <div class="modal-ins-body">
                    <div>
                        <h2>Common Tracking Script</h2>
                        <div>
                            <p>Add following script to all pages of your website. include in common file like header or footer</p>
                            <?php
                                $code = array();
                                $code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
                                echo ___h($code,'html');
                            ?>
                        </div>
                        
                        <div>
                        <?php if(in_array($tool_type, ['general_click'])){ ?>
                            <h2>General Click Tracking</h2>
                            <div>
                                <p>
                                    Use Following code to track genreal clicks of website.
                                </p>    
                                <?php
                                    $code = array();
                                    $code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
                                    $code[] = '<script type="text/javascript">';
                                    $code[] = ' AffTracker.setWebsiteUrl( "WebsiteUrl" );';
                                    $code[] = ' AffTracker.generalClick( "'. $general_code .'" );';
                                    $code[] = '</script>';
                                    
                                    echo ___h($code,'html');
                                ?>

                                <p>
                                    <h6>All possible tracking parameters</h6>
                                    <div class="well">
                                        <strong>WebsiteUrl</strong>       : Website root URL <br>
                                        <strong>general_code</strong> : Unique code of general click like (home,about,contact-us) without any space or special charector.
                                    </div>
                                </p>
                            </div>
                        <?php } ?>
                        
                        <?php if(in_array($tool_type, ['action'])){ ?>
                            <h2>CPA - COST PER ACTION</h2>
                            <div>
                                <p>Any Action like Registration / leads / contuct Form Sent / And any other action, will be on this section per action commissions.</p>
                                <p>Under Integrations>>Integration Tools >> Create new Ads [Banner/Text/Link/Video].</p>
                                <p>Last Step Is To Insert the JavaScript tracking code to the page that should trigger the action.</p>
                                <p>For Example: In Case of "Registration" Action, it should be a page that is displayed after the user register.</p>

                                <?php
                                    $code = array();
                                    $code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
                                    $code[] = '<script type="text/javascript">';
                                    $code[] = ' AffTracker.setWebsiteUrl( "WebsiteUrl" );';
                                    $code[] = ' AffTracker.createAction( "'. $action_code .'" )';
                                    $code[] = '</script>';
                                    
                                    echo ___h($code,'html');
                                ?>

                                <p>
                                    <h6>All possible tracking parameters</h6>
                                    <div class="well">
                                        <strong>WebsiteUrl</strong>       : Website root URL <br>
                                        <strong>actionCode</strong>       : Action code you have added when create a new program tool like Banner Ads/Text Ads/Link Ads/Video Ads<br>
                                    </div>
                                </p>
                            </div>

                        <?php } ?>
                            

                        <?php if(in_array($tool_type, ['program'])){ ?>
                            <h2>Order Tracking</h2>
                            <div>
                                <p>
                                    To track whole order,  add following code to your thank you page or order success page
                                </p>

                                <?php
                                    $code = array();
                                    $code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
                                    $code[] = '<script type="text/javascript">';
                                    $code[] = ' AffTracker.setWebsiteUrl( "WebsiteUrl" );';
                                    $code[] = ' AffTracker.add_order({';
                                    $code[] = '     order_id        : "OrderId",';
                                    $code[] = '     order_currency  : "OrderCurrency",';
                                    $code[] = '     order_total     : "OrderTotal",';
                                    $code[] = '     product_ids     : "ProductIDs"';
                                    $code[] = ' })';
                                    $code[] = '</script>';
                                    
                                    echo ___h($code,'html');
                                ?>

                                <p>
                                    <h6>All possible tracking parameters</h6>
                                    <div class="well">
                                        <strong>WebsiteUrl</strong>       : Website root URL <br>
                                        <strong>OrderId</strong>       : Unique Order ID <br>
                                        <strong>OrderCurrency</strong> : Currency Symball of Order <br>
                                        <strong>OrderTotal</strong>    : Total amount of order <br>
                                        <strong>ProductIDs</strong>    : product ids of order, comma separated string <br>
                                    </div>

                                    <div class="alert alert-info">
                                        <strong>Script Tag</strong> Script tag is optional if you already added in your header or footer. but header and footer must be include on checkout thank you page
                                    </div>
                                </p>

                                <h6>PHP Example</h6>
                                <?php
                                    $code = array();
                                    $code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
                                    $code[] = '<script type="text/javascript">';
                                    $code[] = ' AffTracker.setWebsiteUrl( "WebsiteUrl" );';
                                    $code[] = ' AffTracker.add_order({';
                                    $code[] = '     order_id        : "<?php echo $variable_OrderId ?>",';
                                    $code[] = '     order_currency  : "<?php echo $variable_OrderCurrency ?>",';
                                    $code[] = '     order_total     : "<?php echo $variable_OrderTotal ?>",';
                                    $code[] = '     product_ids     : "<?php echo $variable_ProductIDs ?>"';
                                    $code[] = ' })';
                                    $code[] = '</script>';
                                    
                                    echo ___h($code,'html');
                                ?>
                                </br>
                            </div>

                            <br><hr>

                            <h2>Stop recurring payments of order</h2>
                                <div>
                                    <p>
                                        To stop recurring payments of order,  add following code to stop recurring page for example "stop-membership.php"
                                    </p>

                                    <?php
                                    $code = array();
                                    $code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
                                    $code[] = '<script type="text/javascript">';
                                    $code[] = ' AffTracker.setWebsiteUrl( "WebsiteUrl" );';
                                    $code[] = ' AffTracker.stop_recurring("$variable_OrderId ")';
                                    $code[] = '</script>';
                                    
                                    echo ___h($code,'html');
                                    ?>

                                    <p>
                                        <h6>All possible tracking parameters</h6>
                                        <div class="well">
                                            <strong>WebsiteUrl</strong> : Website root URL <br>
                                            <strong>variable_OrderId </strong>    : Unique Order ID <br>
                                        </div>

                                        <div class="alert alert-info">
                                            <strong>OrderId</strong> variable_OrderId  is must match with "Order Tracking" param variable_OrderId 
                                        </div>
                                    </p>
                                </br>
                            </div>
                        <?php } ?>



                        <?php if(in_array($tool_type, ['program'])){ ?>
                            <h2>Product Click Tracking</h2>
                            <div>
                                <p>
                                    Use Following code to your product details page so system can track click of products.
                                </p>

                            
                                <?php
                                    $code = array();
                                    $code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
                                    $code[] = '<script type="text/javascript">';
                                    $code[] = ' AffTracker.setWebsiteUrl( "WebsiteUrl" );';
                                    $code[] = ' AffTracker.productClick( "ProductID" );';
                                    $code[] = '</script>';
                                    
                                    echo ___h($code,'html');
                                ?>

                                <p>
                                    <h6>All possible tracking parameters</h6>
                                    <div class="well">
                                        <strong>WebsiteUrl</strong>       : Website root URL <br>
                                        <strong>ProductID</strong> : Unique Product id.
                                    </div>
                                </p>

                            </div>
                            </br>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        setTimeout(function(){
            $(".token.string").each(function(){
                var c = $(this).text().replace(/[^a-z_0-9\s]/gi, '')
                $(this).addClass(c)
            });
            $(".auto-fill-filed input").trigger("keyup");

            const clipboard = new Clipboard('.copy', {
              target: (trigger) => {
                return trigger.nextElementSibling;
              }
            });

            clipboard.on('success', (event) => {
              event.trigger.textContent = 'copied!';
              setTimeout(() => {
                event.clearSelection();
                event.trigger.textContent = 'copy';
              }, 2000);
            });

        }, 1000);
    })

    $(".auto-fill-filed input").keyup(function(){
        var val = $(this).val();
        val = val ? val : $(this).attr("data-default");

        $("." +$(this).attr("name")  ).text('"' + val +'"')
    });
</script>