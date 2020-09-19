<div class="row mb-5">
	<div class="col-sm-4 mb-5">
		<div class="card">
			<div class="card-body">
				<h4 class="text-center"><span class="pull-left"> <?= (int)$statistics['clicks_count'] ?></span> <?= __('admin.click_by_country') ?></h4>
				<?php if((int)$statistics['clicks_count'] > 0){ ?>
					<ul class="list-unstyled list-inline text-center">
	                    <?php $i = 0; foreach($statistics['clicks'] as $country => $counts){ ?>
	                        <li class="list-inline-item">
	                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
	                        </li>
	                    <?php } ?>
					</ul>
					<div id="clicks-chart" style="height:300px;"></div>
				<?php } else { ?>
					<div class="empty-graph">
						NO ACTIVITY
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

	<div class="col-sm-4 mb-5">
		<div class="card">
			<div class="card-body">
				<h4 class="text-center"><span class="pull-left"> <?= (int)$statistics['action_clicks_count'] ?></span> Action Click by Country</h4>
				<?php if((int)$statistics['action_clicks_count'] > 0){ ?>
					<ul class="list-unstyled list-inline text-center">
	                    <?php $i = 0; foreach($statistics['action_clicks'] as $country => $counts){ ?>
	                        <li class="list-inline-item">
	                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
	                        </li>
	                    <?php } ?>
					</ul>
					<div id="action_click-chart" style="height:300px;"></div>
				<?php } else { ?>
					<div class="empty-graph">
						NO ACTIVITY
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

	<div class="col-sm-4 mb-5">
		<div class="card">
			<div class="card-body">
				<h4 class="text-center"><span class="pull-left"> <?= (int)$statistics['sale_count'] ?></span> <?= __('admin.sale_by_country') ?></h4>
				<?php if((int)$statistics['sale_count'] > 0){ ?>
					<ul class="list-unstyled list-inline text-center">
	                    <?php $i = 0; foreach($statistics['sale'] as $country => $counts){ ?>
	                        <li class="list-inline-item">
	                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
	                        </li>
	                    <?php } ?>
					</ul>
					<div id="sale-chart" style="height:300px;"></div>
				<?php } else { ?>
					<div class="empty-graph">
						NO ACTIVITY
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

	
</div>

<div class="row ">
	<div class="col-sm-6 mb-5">
		<div class="card">
			<div class="card-body">
				<h4 class="text-center"><span class="pull-left"> <?= (int)$statistics['affiliate_user_count'] ?></span> <?= __('admin.affiliate_user_by_country') ?></h4>
				<?php if((int)$statistics['affiliate_user_count'] > 0){ ?>
					<ul class="list-unstyled list-inline text-center">
	                    <?php $i = 0; foreach($statistics['affiliate_user'] as $country => $counts){ ?>
	                        <li class="list-inline-item">
	                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
	                        </li>
	                    <?php } ?>
					</ul>
					<div id="affiliate_user-chart" style="height:300px;"></div>
				<?php } else { ?>
					<div class="empty-graph">
						NO ACTIVITY
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

	<div class="col-sm-6 mb-5">
		<div class="card">
			<div class="card-body">
				<h4 class="text-center"><span class="pull-left"> <?= (int)$statistics['client_user_count'] ?></span> <?= __('admin.client_by_country') ?></h4>
				<?php if((int)$statistics['client_user_count'] > 0){ ?>
					<ul class="list-unstyled list-inline text-center">
	                    <?php $i = 0; foreach($statistics['client_user'] as $country => $counts){ ?>
	                        <li class="list-inline-item">
	                            <p><i class="mdi mdi-checkbox-blank-circle <?php echo 'color-'.$i++ % 5 ; ?> mr-2"></i><?php echo $country; ?></p>
	                        </li>
	                    <?php } ?>
					</ul>
					<div id="client_user-chart" style="height:300px;"></div>
				<?php } else { ?>
					<div class="empty-graph">
						NO ACTIVITY
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).on('ready',function() {
		var colorss = ['#40a4f1', '#5b6be8', '#c1c5e2', '#e785da', '#00bcd2'];
		
		if($("#clicks-chart").length){
			var donutData = [
				<?php $str = '';
					foreach($statistics['clicks'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
					echo $str;
				?>
			];
			Morris.Donut({
				element: 'clicks-chart',
				data: donutData,
				resize: true,
				colors: colorss,
			});
		}

		if($("#action_click-chart").length){
			var donutData = [
				<?php $str = '';
					foreach($statistics['action_clicks'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
					echo $str;
				?>
			];
			Morris.Donut({
				element: 'action_click-chart',
				data: donutData,
				resize: true,
				colors: colorss,
			});
		}

		if($("#sale-chart").length){

			var donutData = [
				<?php $str = '';
					foreach($statistics['sale'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
					echo $str;
				?>
			];
			Morris.Donut({
				element: 'sale-chart',
				data: donutData,
				resize: true,
				colors: colorss,
			});
		}


		if($("#affiliate_user-chart").length){
			var donutData = [
				<?php $str = '';
					foreach($statistics['affiliate_user'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
					echo $str;
				?>
			];
			Morris.Donut({
				element: 'affiliate_user-chart',
				data: donutData,
				resize: true,
				colors: colorss,
			});
		}

		if($("#client_user-chart").length){
			var donutData = [
				<?php $str = '';
					foreach($statistics['client_user'] as $country=>$counts){ $str .= '{label: "' . $country . '", value: ' . $counts . '},'; }
					echo $str;
				?>
			];
			Morris.Donut({
				element: 'client_user-chart',
				data: donutData,
				resize: true,
				colors: colorss,
			});
		}
		
	});
</script>