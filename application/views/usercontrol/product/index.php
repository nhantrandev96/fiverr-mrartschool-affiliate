<?php
    $db =& get_instance();
    $userdetails=$db->userdetails();
    $store_setting =$db->Product_model->getSettings('store');
?>
<div class="row">
    <div class="col-<?= $store_setting['status'] ? '12' : '12' ?>">
        <?php if($store_setting['status']){ ?>
        <div class="col-12">
            <div class="card m-b-20">
                <div class="card-header m-b-10">
                    <h4 class="mt-0 header-title pull-left m-0"><?= __('admin.local_store_overview') ?></h4>
                    <div class="pull-right">
                        <?php if($totals['store']['hold_orders']){?>
                        <div   data-toggle="tooltip" title="Hold Orders">
                            <a href="<?= base_url('admincontrol/mywallet') ?>" class="order-hold-noti">
                            <i class="fa fa-bell"></i>
                            <span><?= $totals['store']['hold_orders'] ?></span>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div role="tabpanel">
                    <div role="tabpanel" class="tab-pane active" id="all-store">
                        <ul class="list-group">
                            <li class="list-group-item"><?php echo __( 'admin.local_store_aff_pro' ) ?></li>
                            <li class="list-group-item"><?php echo __( 'admin.total_balance' ) ?>
                                <span class="badge badge-primary badge-pill font-14 pull-right">
                                <?php echo c_format($totals['store']['balance']) ?></span>
                            </li>
                            <li class="list-group-item"><?php echo __( 'admin.total_sales' ) ?>
                                <span class="badge badge-primary badge-pill font-14 pull-right">
                                <?php echo c_format($totals['store']['balance']) ?> / <?php echo c_format($totals['all_sale_comm']) ?></span>
                            </li>
                            <li class="list-group-item"><?php echo __( 'admin.total_clicks' ) ?>
                                <span class="badge badge-primary badge-pill font-14 pull-right">
                                <?php echo (int)$totals['store']['click_count'] ?> /  <?php echo c_format($totals['store']['click_amount']) ?></span>
                            </li>
                            <li class="list-group-item"><?php echo __( 'admin.total_commission' ) ?>
                                <span class="badge badge-primary badge-pill font-14 pull-right">
                                <?php echo c_format($totals['store']['total_commission']) ?></span>
                            </li>
                            <li class="list-group-item"><?php echo __( 'admin.total_orders' ) ?>
                                <span class="badge badge-primary badge-pill font-14 pull-right">
                                <?php echo $ordercount; ?></span>
                            </li>
                            <li class="list-group-item">
                                <span class="badge badge-light"><?php $store_url = base_url('store'); ?></span>
                                <a class="btn btn-lg btn-default btn-success" href="<?php echo $store_url ?>"
                                    target="_blank"><?= __('admin.priview_store') ?></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="">
                    <div>
                        <b><?= __('user.store_nurl') ?></b>
                        <div class="row">
                            <div class="col-sm-8">
                                <?php $store_url = base_url('store/'.base64_encode($userdetails['id']) ); ?>
                                <div class="input-group">
                                    <input type="text" id="store-link" readonly="readonly" value="<?php echo $store_url ?>" class="form-control">
                                    <button onclick="copy_text()" class="input-group-addon">
                                    <img src="<?php echo base_url('assets/images/clippy.svg') ?>" class="tooltiptext" width="25px" height="25px" alt="Copy to clipboard">
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="share-store-list">
                                    <a href="<?php echo $store_url ?>" target="_blank"><?= __('user.priview_nstore') ?></a>
                                    <a onclick="shareinsocialmedia('https://www.facebook.com/sharer/sharer.php?u=<?php echo $store_url ?>&amp;title=Buy Product and earn by affiliate program')" href="javascript:void(0)"><i class="fa fa-facebook fa-6" aria-hidden="true"></i></a>
                                    <a onclick="shareinsocialmedia('https://plus.google.com/share?url=<?php echo $store_url ?>/<?php echo $user['id'];?>')" href="javascript:void(0)"><i class="fa fa-google-plus fa-6" aria-hidden="true"></i></a>
                                    <a onclick="shareinsocialmedia('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $store_url ?>/<?php echo $user['id'];?>&amp;title=Buy Product and earn by affiliate program')" href="javascript:void(0)"><i class="fa fa-linkedin fa-6" aria-hidden="true"></i></a>
                                    <a onclick="shareinsocialmedia('http://twitter.com/home?status=Buy Product and earn by affiliate program+<?php echo $store_url ?>/<?php echo $user['id'];?>')" href="javascript:void(0)"><i class="fa fa-twitter fa-6" aria-hidden="true"></i></a>
                                    <a href="mailto:?subject=Buy Product and earn by affiliate program&amp;body=Check out this site <?= $productLink ?>" title="Share by Email">
                                    <i class="fa fa-envelope cursors" aria-hidden="true" style="color:#2a3f54"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        
                        <div class="text-center empty-div d-none">
                            <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
                            <h3 class="m-t-40 text-center"><?= __('admin.no_products') ?></h3>
                        </div>
                    
                        <div class="table-responsive">
                            <table id="tech-companies-1" class="table  table-striped">
                                <thead>
                                    <tr>
                                        <th><?= __('user.name') ?></th>
                                        <th width="60px"><?= __('user.featured_nimage') ?></th>
                                        <th><?= __('user.price') ?></th>
                                        <th><?= __('user.sku') ?></th>
                                        <th width="220px"><?= __('user.get_ncommission') ?></th>
                                        <th><?= __('user.sales_n_ncommission') ?></th>
                                        <th><?= __('user.clicks_n_ncommission') ?></th>
                                        <th><?= __('user.total_ncommission') ?></th>
                                        <th width="160px"><?= __('user.action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="12" class="text-right">
                                            <ul class="pagination pagination-td"></ul>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="model-codemodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" async="">
    $(".toggle-child-tr").on('click',function(){
    	$tr = $(this).parents("tr");
    	$ntr = $tr.next("tr.detail-tr");
    
    	if($ntr.css("display") == 'table-row'){
    		$ntr.hide();
    		$(this).find("i").attr("class","fa fa-plus");
    	}else{
    		$(this).find("i").attr("class","fa fa-minus");
    		$ntr.show();
    	}
    })
    $(".show-more").on('click',function(){
    	$(this).parents("tfoot").remove();
    	$("#product-list tr.d-none").hide().removeClass('d-none').fadeIn();
    });
    
    function generateCode(affiliate_id){
        $this = $(this);
        $.ajax({
            url:'<?php echo base_url();?>usercontrol/generateproductcode/'+affiliate_id,
            type:'POST',
            dataType:'html',
            beforeSend:function(){
                $this.btn("loading");
            },
            complete:function(){
                $this.btn("reset");
            },
            success:function(json){
                $('#model-codemodal .modal-body').html(json)
                $("#model-codemodal").modal("show")
            },
        })
    }

    $("#filter-form").on("submit",function(){
        getPage('<?= base_url("usercontrol/listproduct_ajax/") ?>/1');
        return false;
    })

    function getPage(url){
        $this = $(this);

        $.ajax({
            url:url,
            type:'POST',
            dataType:'json',
            data:$("#filter-form").serialize(),
            beforeSend:function(){$this.btn("loading");},
            complete:function(){$this.btn("reset");},
            success:function(json){
                if(json['view']){
                    $("#tech-companies-1 tbody").html(json['view']);
                    $("#tech-companies-1").show();
                } else {
                    $(".empty-div").removeClass("d-none");
                    $("#tech-companies-1").hide();
                }
                
                $("#tech-companies-1 .pagination-td").html(json['pagination']);
            },
        })
    }

    getPage('<?= base_url("usercontrol/listproduct_ajax/") ?>/1');
    $("#tech-companies-1 .pagination-td").delegate("a","click",function(){
        getPage($(this).attr("href"));
        return false;
    })
</script>