<?php
	$db =& get_instance();
	$userdetails=$db->userdetails();
	$store_setting =$db->Product_model->getSettings('store');
?>
<div class="row">
	<div class="col-lg-12 col-md-12">
		<?php if($this->session->flashdata('success')){?>
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<?php echo $this->session->flashdata('success'); ?> </div>
		<?php } ?>
		<?php if($this->session->flashdata('error')){?>
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<?php echo $this->session->flashdata('error'); ?> </div>
		<?php } ?>
	</div>
</div>

<link rel="stylesheet" type="text/css" href="<?= base_url('assets/integration/prism/css.css') ?>?v=<?= av() ?>">
<script type="text/javascript" src="<?= base_url('assets/integration/prism/js.js') ?>"></script>


<?php 
	function ___h($text,$lan){
		$text = implode("\n", $text);
		$text = htmlentities($text);
		$text = '<pre class="language-'.$lan.'"><code class="language-'.$lan.'">'.$text.'</code></pre>';
		return $text;
	}
	$base_url  = base_url();
?>
	
<div class="row">
	<div class="col-sm-12">
	    <div class="card">
	    	<div class="card-header">
	    		<h4 class="card-title">How to create payment method</h4>
	    	</div>
	    	<div class="card-body payment-doc">
	    		<p>There are 3 payment methods available in the local store itself and. Although sometimes you'll find yourself in the situation where you need something different, either there is no method available for your choice of payment gateway or you want some different logic. In either case, you're left with the only option: To create a new payment method module in Store.</p>

	    		<p>We'll assume that our custom payment method name is "custom". There are at least three files you need to create in order to set up the things. Let's check the same in detail.</p>

	    		<p>You need to create three file. each file are required</p>
	    		<ol>
	    			<li>controller</li>
	    			<li>views</li>
	    			<li>setting</li>
	    		</ol>

	    		<div class="steps">
		    		<div class="steps-header"><h3>Setting Up the Setting File</h3></div>
		    		<div class="steps-body">
			    		<p>Go ahead and create the setting file at <code>application/payments/settings/custom.php</code>. Paste the following contents in the newly created setting file custom.php.</p>
			    		<?php
							$code = array();
							$code[] = '<div class="form-group">';
							$code[] = '	<label class="control-label">Status</label>';
							$code[] = '	<select class="form-control" name="status">';
							$code[] = '		<option <?= (int)$setting_data[\'status\'] == "0" ? "selected" : "" ?> value="0">Disabled</option>';
							$code[] = '		<option <?= (int)$setting_data[\'status\'] == "1" ? "selected" : "" ?> value="1">Enabled</option>';
							$code[] = '	</select>';
							$code[] = '</div>';
							echo ___h($code,'php');
						?>

						<p>in this file you can define all setting for admin. like get status, api key, sanbbox details etc.. all setting data you will get inside <code>setting_data</code> variable. and all your setting are save under setting group name conversation like "storepayment_[payment_method_name]" you can get inside controller file</p>
					</div>
	    		</div>

				<div class="steps">
					<div class="steps-header"><h3>Setting Up the View</h3></div>
					<div class="steps-body">
			    		<p>Go ahead and create the view file at <code>application/payments/views/custom.php</code>. Paste the following contents in the newly created view file custom.php.</p>

			    		<?php
							$code = array();
							$code[] = '<button class="btn btn-default" onclick="backCheckout()">Back</button>';
							$code[] = '<button class="btn btn-primary" id="button-confirm">Confirm</button>';
							$code[] = '<script type="text/javascript">';
							$code[] = '	$("#button-confirm").click(function(){';
							$code[] = '		$this = $(this);';
							$code[] = '		$.ajax({';
							$code[] = '			url:\'<?= $base_url ?>/store/confirm_payment\',';
							$code[] = '			type:"POST",';
							$code[] = '			dataType:"json",';
							$code[] = '			data:{';
							$code[] = '				comment:$(\'textarea[name="comment"]\').val(),';
							$code[] = '			},';
							$code[] = '			beforeSend:function(){';
							$code[] = '				$this.btn("loading");';
							$code[] = '			},';
							$code[] = '			complete:function(){';
							$code[] = '				$this.btn("reset");';
							$code[] = '			},';
							$code[] = '			success:function(json){';
							$code[] = '				if(json[\'redirect\']){';
							$code[] = '					window.location = json[\'redirect\'];';
							$code[] = '				}';
							$code[] = '				if(json[\'warning\']){';
							$code[] = '					alert(json[\'warning\'])';
							$code[] = '				}';
							$code[] = '			},';
							$code[] = '		})';
							$code[] = '	})';
							$code[] = '</script>';


							echo ___h($code,'php');
						?>

						<p>this file is last step of checkout. its confirm order you have to do confirm order on <code>/store/confirm_paymen</code> this url call your confirm method on controller file</p>
					</div>
				</div>

				<div class="steps">
		    		<div class="steps-header"><h3>Setting Up the Controller</h3></div>
		    		<div class="steps-body">
			    		<p>Go ahead and create the controller file at <code>application/payments/controllers/custom.php</code>. Paste the following contents in the newly created controller file custom.php.</p>

			    		<?php
							$code = array();
							$code[] = 'class custom {';
							$code[] = '	public $title = \'Custom name\';';
							$code[] = '	public $icon = "assets/images/payments/custom.png");';
							$code[] = '	public $website = "http::custom.com");';
							$code[] = '	function __construct($api){ $this->api = $api; }';
							$code[] = '	public function confirm($data) {';
							$code[] = '		$json[\'success\'] = true;';
							$code[] = '		$json[\'redirect\'] = $data[\'thankyou_url\'];';
							$code[] = '		$this->api->confirm_order_api($data[\'order_info\'][\'id\'],7);';
							$code[] = '		return $json;';
							$code[] = '	}';
							$code[] = '	public function getMethod($data){';
							$code[] = '		return array(';
							$code[] = '			\'html\' => \'<p>Custom name</p>\',';
							$code[] = '			\'image\' => \'\',';
							$code[] = '		);';
							$code[] = '	}';
							$code[] = '}';
							echo ___h($code,'php');
						?>

						<div class="func-desc">
							<?php
								$code = array();
								$code[] = 'public function getMethod($data){}';
								echo ___h($code,'php');
							?>
							<p>This function use to get a name or image of payment gateway. image param is optional</p>

							Inside <code>getMethod($data)</code> you will get following values in side $data array
							<ol>
								<li><code>products</code> : cart products list</li>
								<li><code>order_id</code> : sub total of cart</li>
								<li><code>order_info</code> : sub total of cart</li>
							</ol>
						</div>

						<div class="func-desc">
							<?php
								$code = array();
								$code[] = 'public function confirm($data){}';
								echo ___h($code,'php');
							?>
							<p>This function use to confirm order. functon is call from view file</p>

							Inside <code>confirm($data)</code> you will get following values in side $data array
							<ol>
								<li><code>base_url</code> : base url of application</li>
								<li><code>thankyou_url</code> : url of thank you page</li>
								<li><code>order_info</code> : array of order info</li>
								<li><code>products</code> : array of order products</li>
								<li><code>setting_data</code> : array of your settings</li>
							</ol>
						</div>

						<div class="func-desc">
							<?php
								$code = array();
								$code[] = '$this->api->confirm_order_api($order_id, $status, $transaction_id = "", $comment = "")';
								echo ___h($code,'php');
							?>
							<p>this function Use to confirm order. add order history</p>
							<ol>
								<li><code>order_id</code> : order id you can get from $data['order_info']['id']</li>
								<li><code>status</code> : status id you can get from below list</li>
								<li><code>transaction_id</code> : its optional. if you have transaction_id than pass it</li>
								<li><code>comment</code> : its optional. if have any comment for order than pass it</li>
							</ol>
						</div>
						<div class="func-desc">
							<p>How to get config setting data</p>
							<?php
								$code = array();
								$code[] = '$setting = $this->api->Product_model->getSettings(\'storepayment_custom\');';
								echo ___h($code,'php');
							?>
						</div>
						<div class="func-desc">
							<p>How to call your custom method outside from controllers file</p>
							<p>
								[base_url]/callbackfunctions/[payment_method_name]/[custom_function_name]/[function_argument]
							</p>
							<p> 
								For example inside your controller have notify($order_id) method. than your calling url is like this
								<?php
									$code = array();
									$code[] = '$url = base_url("store/callbackfunctions/custom/notify/1");';
									echo ___h($code,'php');
								?>
							</p>
						</div>
					</div>
				</div>

				<div class="steps">
					<div class="steps-header"><h3>Order Status ID and Titles</h3></div>
					<div class="steps-body p-0">
						<table class="table-striped table table-sm">
							<tr><th width="90px">Status ID</th><th>Title</th></tr>
							<tr><td>0</td><td>Waiting For Payment</td></tr>
					        <tr><td>1</td><td>Complete</td></tr>
					        <tr><td>2</td><td>Total not match</td></tr>
					        <tr><td>3</td><td>Denied</td></tr>
					        <tr><td>4</td><td>Expired</td></tr>
					        <tr><td>5</td><td>Failed</td></tr>
					        <tr><td>6</td><td>Pending</td></tr>
					        <tr><td>7</td><td>Processed</td></tr>
					        <tr><td>8</td><td>Refunded</td></tr>
					        <tr><td>9</td><td>Reversed</td></tr>
					        <tr><td>10</td><td>Voided</td></tr>
					        <tr><td>11</td><td>Canceled Reversal</td></tr>
						</table>
					</div>
				</div>

	    	</div>
		</div>
    </div>
</div>
