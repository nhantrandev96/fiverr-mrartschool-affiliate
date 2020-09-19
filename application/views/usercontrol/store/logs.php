<div class="clearfix"></div>
<br>
<div class="card">
	<div class="card-header">
		<h4 class="card-title">Click Logs</h4>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table click-table">
				<thead>
					<tr>
						<th width="80px">#</th>
						<th width="80px">Click ID</th>
						<th>Website</th>
						<th>Ip</th>
						<th>Created AT</th>
						<th>Click Type</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
	<div class="card-footer text-right" style="display: none;"> <div class="pagination"></div> </div>
</div>


<script type="text/javascript">
	 $(".click-table").delegate(".toggle-child-tr","click",function(){
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
    
	function getPage(page,t) {
		$this = $(t);
		$.ajax({
			url:'<?= base_url("usercontrol/store_logs") ?>/' + page,
			type:'POST',
			dataType:'json',
			data:{page:page},
			beforeSend:function(){$this.btn("loading");},
			complete:function(){$this.btn("reset");},
			success:function(json){
				$(".click-table tbody").html(json['html']);
				$(".card-footer").hide();
				
				if(json['pagination']){
					$(".card-footer").show();
					$(".card-footer .pagination").html(json['pagination'])
				}
			},
		})
	}

	$(".card-footer .pagination").delegate("a","click", function(e){
		e.preventDefault();
		getPage($(this).attr("data-ci-pagination-page"),$(this));
	})

	getPage(1)
</script>