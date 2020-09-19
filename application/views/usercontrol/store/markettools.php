<?php
    $db =& get_instance();
    $userdetails=$db->userdetails();
?>

<div class="row">
    <div class="col-sm-12">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="col-12">
                    <div>
                        <h5 class=""><?php echo __('user.affiliates_links...') ?></h5>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <label class="control-label">Search By Market Category</label>
                                <select class="form-control category_id" >
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $key => $value) { ?>
                                        <option value="<?= $value['value'] ?>"><?= $value['label'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Search By Ads Name</label>
                                <input class="table-search form-control ads_name" placeholder="Search" type="search">
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label">Search By Store Category</label>
                                <select class="form-control market_category_id" >
                                    <option value="">All Categories</option>
                                    <?php foreach ($store_categories as $key => $value) { ?>
                                        <option value="<?= $value['value'] ?>"><?= $value['label'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="control-label d-block">&nbsp;</label>
                                <label class="checkbox">
                                    <input type="checkbox" class="display-vendor-links"> Display Vendor Links
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="card-body p-0" style="height: 100%;overflow: auto;">
                    <div class="text-center empty-div d-none">
                        <img class="img-responsive" src="<?php echo base_url(); ?>assets/vertical/assets/images/no-data-2.png" style="margin-top:25px;">
                        <h3 class="m-t-40 text-center"><?= __('user.no_banners_to_share_yet') ?></h3>
                    </div>
                
                    <div class="table-responsive b-0" >
                        <table id="product-list" class="table table-no-wrap">
                            <thead>
                                <tr>
                                    <th class="text-center" width="1"></th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Commission</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                           <tbody></tbody>
                        </table>
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
<div class="modal" id="model-codeformmodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="integration-code"><div class="modal-dialog"><div class="modal-content"></div></div></div>

<script type="text/javascript">

    var xhr;
    function getPage(url){
        $this = $(this);

        if(xhr && xhr.readyState != 4) xhr.abort();

        xhr = $.ajax({
            url:url,
            type:'POST',
            dataType:'json',
            data:{
                market_category_id: $(".market_category_id").val(),
                category_id: $(".category_id").val(),
                ads_name: $(".ads_name").val(),
                dvl: $(".display-vendor-links").prop('checked'),
            },
            beforeSend:function(){$(".btn-search").btn("loading");},
            complete:function(){$(".btn-search").btn("reset");},
            success:function(json){
                if(json['view']){
                    $("#product-list tbody").html(json['view']);
                    $("#product-list").show();
                    $(".empty-div").addClass("d-none");
                } else {
                    $(".empty-div").removeClass("d-none");
                    $("#product-list").hide();
                }
            },
        })
    }

    $(".category_id,.market_category_id, .display-vendor-links").on("change",function(){
        getPage('<?= base_url("usercontrol/store_markettools/") ?>/1');
    });
    $(".ads_name").on("keyup",function(){
        getPage('<?= base_url("usercontrol/store_markettools/") ?>/1');
    });
    
    getPage('<?= base_url("usercontrol/store_markettools/") ?>/1');



    $("#product-list").delegate(".get-code",'click',function(){
        $this = $(this);
        $.ajax({
            url:'<?= base_url("integration/tool_get_code/usercontrol") ?>',
            type:'POST',
            dataType:'json',
            data:{id:$this.attr("data-id")},
            beforeSend:function(){ $this.btn("loading"); },
            complete:function(){ $this.btn("reset"); },
            success:function(json){
                if(json['html']){
                    $("#integration-code .modal-content").html(json['html']);
                    $("#integration-code").modal("show");
                }
            },
        })
    })

    $("#product-list").delegate(".toggle-child-tr",'click',function(){
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
    $("#product-list").delegate(".show-more",'click',function(){
        $(this).parents("tfoot").remove();
        $("#product-list tr.d-none").hide().removeClass('d-none').fadeIn();
    });

    function generateCode(affiliate_id,t){
        $this = $(t);
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

    function generateCodeForm(form_id,t){ 
        $this = $(t);
        $.ajax({
            url:'<?php echo base_url();?>usercontrol/generateformcode/'+form_id,
            type:'POST',
            dataType:'html',
            beforeSend:function(){
                $this.btn("loading");
            },
            complete:function(){
                $this.btn("reset");
            },
            success:function(json){
                $('#model-codeformmodal .modal-body').html(json)
                $("#model-codeformmodal").modal("show")
            },
        })
    }
   
   
</script>