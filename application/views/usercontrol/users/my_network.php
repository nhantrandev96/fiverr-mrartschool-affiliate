<div class="row">
					<div class="col-sm-3">
						<div class="card m-b-30 text-white bg-info">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <h6><?= (int)$refer_total['total_product_click']['clicks'] ?> /
		                        <?= c_format($refer_total['total_product_click']['amounts']) ?></h6>
                                 	<a><p class="mb-0 text-muted"><?= __('user.referals_product_click_commissions') ?></p></a>
                                </blockquote>
                            </div>
                        </div>
					</div>
					<div class="col-sm-3">
						<div class="card m-b-30 text-white bg-info">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <h6><?= (int)$refer_total['total_product_sale']['counts'] ?> /
		                        <?= c_format($refer_total['total_product_sale']['amounts']) ?></h6>
                                 	<a><p class="mb-0 text-muted"><?= __('user.referals_sale_commissions') ?></p></a>
                                </blockquote>
                            </div>
                        </div>
					</div>
					<div class="col-sm-3">
						<div class="card m-b-30 text-white bg-info">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <h6><?= (int)$refer_total['total_ganeral_click']['total_clicks'] ?> /
		                        <?= c_format($refer_total['total_ganeral_click']['total_amount']) ?></h6>
                                 	<a><p class="mb-0 text-muted"><?= __('user.referals_general_click_commissions') ?></p></a>
                                </blockquote>
                            </div>
                        </div>
					</div>
					<div class="col-sm-3">
						<div class="card m-b-30 text-white bg-info">
                            <div class="card-body">
                                <blockquote class="card-bodyquote mb-0">
                                    <h6>
                                        <?= (int)$refer_total['total_action']['click_count'] ?> /
		                        <?= c_format($refer_total['total_action']['total_amount']) ?>
                                    </h6>
                                 	<a><p class="mb-0 text-muted"><?= __('user.referals_action_commissions') ?></p></a>
                                </blockquote>
                            </div>
                        </div>
					</div>
					
				</div>

<div class="row">
	<div class="col-12">
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#tab-menu_referring_tree"><?= __('user.menu_referring_tree') ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#tab-referred_users_tree"><?= __('user.referred_users_tree') ?></a>
			</li>
		
		</ul>

		<div class="tab-content">
			<div class="tab-pane" id="tab-referred_users_tree">
				<div class="card card-tree">
					<div class="card-header">
						<h4 class="card-title"><?= __('user.referred_users_tree') ?></h4>
						<div class="action">
							<button class="btn btn-primary btn-sm btn-tree-action" onclick='$("#tree").fancytree("getTree").expandAll();'>Open All</button>
							<button class="btn btn-primary btn-sm btn-tree-action" onclick='$("#tree").fancytree("getTree").expandAll(false);'>Close all</button>
						</div>
					</div>
					<div class="card-body">

						<script type="text/javascript" src="<?= base_url('assets/plugins/ui/jquery-ui.min.js') ?>"></script>
						<link href="<?= base_url('assets/plugins/fancytree/skin-win8/ui.fancytree.css') ?>?v=<?= av() ?>" rel="stylesheet" />
					    <script src="<?= base_url('assets/plugins/fancytree/jquery.fancytree.js') ?>"></script>
					    <script src="<?= base_url('assets/plugins/fancytree/jquery.fancytree.table.js') ?>"></script>
					    
					    <script type="text/javascript">
					    	$(function() {
					    		$("#tree").fancytree({
					    			checkbox: false,
					    			debugLevel: 0,
					    			checkboxAutoHide: true,
					    			titlesTabbable: true,
					    			source: { url: "<?= base_url('usercontrol/myreferal_ajax') ?>" },
					    			extensions: ["table"],
					    			table: {
					    				indentation: 10,
					    				nodeColumnIdx: 0,
					    				checkboxColumnIdx: 0,
					    			},
					    			renderColumns: function(event, data) {
					    				var node = data.node,
					    				$tdList = $(node.tr).find(">td");

					    				var col1 = node.data.email;
					    				var col2 = (node.data.click + node.data.external_click + node.data.form_click + node.data.aff_click) + ' / ' + node.data.click_commission;
					    				var col3 = node.data.external_action_click + "/" + node.data.action_click_commission;
					    				var col4 = node.data.amount_external_sale_amount + "/" + node.data.sale_commission;
					    				var col5 = node.data.paid_commition + "/" + node.data.unpaid_commition;
					    				var col6 = node.data.in_request_commiton;
					    				var col7 = node.data.all_commition;

					    				$tdList.eq(1).html(col1);
					    				$tdList.eq(2).html(col2);
					    				$tdList.eq(3).html(col3);
					    				$tdList.eq(4).html(col4);
					    				$tdList.eq(5).html(col5);
					    				$tdList.eq(6).html(col6);
					    				$tdList.eq(7).html(col7);
					    			},
					    			modifyChild: function(event, data) {
					    				data.tree.info(event.type, data);
					    			},
					    		})

					    		$("#tree").fancytree("getTree").expandAll();
					    	});
					    </script>
					    <div class="table-responsive">
						    <table id="tree" class="table table-sm">
						    	<colgroup>
						    		<col width="350px" />
						    		<col width="100px" />
						    		<col width="100px" />
						    		<col width="100px" />
						    		<col width="100px" />
						    		<col width="100px" />
						    		<col width="100px" />
						    		<col width="100px" />
						    	</colgroup>
						    	<thead>
						    		<tr>
						    			<th><?= __('admin.name') ?></th>
						    			<th><?= __('admin.email') ?></th>
						    			<th><?= __('admin.clicks') ?> <br><?= __('admin.commissions') ?></th>
						    			<th>Action Click<br>Commission</th>
						    			<th><?= __('admin.sales_commissions') ?></th>
						    			<th><?= __('admin.paid_unpaid') ?> <br> <?= __('admin.commissions') ?></th>
						    			<th><?= __('admin.in_request') ?></th>
						    			<th><?= __('admin.total') ?> <br> <?= __('admin.commissions') ?></th>
						    		</tr>
						    	</thead>
						    	<tbody>
						    		<tr>
						    			<td></td>
						    			<td></td>
						    			<td></td>
						    			<td></td>
						    			<td></td>
						    			<td></td>
						    			<td></td>
						    			<td></td>
						    		</tr>
						    	</tbody>
						    </table>
					    </div>
					</div>
				</div>
			</div>
			<div class="tab-pane active" id="tab-menu_referring_tree">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title"><?= __('user.menu_referring_tree') ?></h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
				            <?php 
				                function buildTree($data){
				                   foreach ($data as $key => $value) {
				                     $html .= '<li> <span>'. $value['name'] .'</span>';
				                        $t = buildTree($value['children']);
				                        if($t) $html .= "<ul>{$t}</ul>";
				                     $html .= '</li>';
				                   }
				                   return $html;
				                }
				                echo "<figure>";
				                echo "<ul class='usertree'>". buildTree($userslist) ."</ul>";
				                echo "</figure>";
				              ?>
				        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		var hash = $(e.target).attr('href');
		if (history.pushState) {
		    history.pushState(null, null, hash);
		} else {
		    location.hash = hash;
		}
	});

	$(document).on('ready',function(){
		var hash = window.location.hash;
		if (hash) { $('.nav-link[href="' + hash + '"]').tab('show'); }
	})
</script>