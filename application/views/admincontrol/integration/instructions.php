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

<script type="text/javascript" src="<?= base_url('assets/plugins/html2canvas/html2canvas.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/plugins/html2canvas/jspdf.debug.js') ?>"></script>
<script type="text/javascript">
	function download(ele){
		$(".no-pdf").hide();
		$(".btn-export-pdf").btn("loading");

		var HTML_Width = $(ele).width();
		var HTML_Height = $(ele).height();

		var top_left_margin = 15;
		var PDF_Width = HTML_Width+(top_left_margin*2);
		var PDF_Height = (PDF_Width*1.5)+(top_left_margin*2);
		var canvas_image_width = HTML_Width;
		var canvas_image_height = HTML_Height;

		var totalPDFPages = Math.ceil(HTML_Height/PDF_Height)-1;

		html2canvas($(ele)[0],{allowTaint:true}).then(function(canvas) {
			canvas.getContext('2d');
			
			var imgData = canvas.toDataURL("image/jpeg", 1.0);
			var pdf = new jsPDF('p', 'pt',  [PDF_Width, PDF_Height]);
		    pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin,canvas_image_width,canvas_image_height);
			
			for (var i = 1; i <= totalPDFPages; i++) { 
				pdf.addPage(PDF_Width, PDF_Height);
				pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
			}
			
		    pdf.save("<?= __('admin.payment_api_documentation') ?>.pdf");

		    $(".no-pdf").show();
		    $(".btn-export-pdf").btn("reset");
        });
	}
</script>

<div class="row" id="page-doc">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h5 class="pull-left"><?= __( 'admin.integration_of' ) ?><?= $module['name'] ?></h5>
				<div class="pull-right">
					<?php if($module_key == 'affiliate_register_api'){ ?>
	    				<button type="button" onclick="download('#page-doc')" class="btn btn-export-pdf btn-primary btn-sm">Download As PDF</button>
	    			<?php } ?>
	    		</div>
			</div>

			<div class="card-body">
				<div class="integration-modules-ins">
					<?= $views ?>

					<?php if($module_key == 'affiliate_register_api'){ ?>
						<div id="affiliate_register_api">
							<p>This page contains the APIs that are used to manage Affiliate Registrations. A registration is the association between a Affiliate and an Affiliate Script that they log into. Here are the APIs:</p>

							<p class="text-info">Download Postman Example <a target="_blank" href="<?= base_url('assets/integration/Affiliate-Pro.postman_collection.json') ?>">Affiliate-Pro.postman_collection.json</a>. How to import postman data file <a href="https://learning.postman.com/docs/running-collections/working-with-data-files/" target="_target" ><i class="fa fa-external-link"></i></a></p>
							<p class="text-info">Download PHP Bootstrap Example <a download target="_blank" href="<?= base_url('assets/register-api-example.zip') ?>"> Download </a></p>


							<h5 class="mt-5">Get Custom Field For Registration</h5>
							<hr>
							<p>For get custom field you need to call this api</p>


							<h6>URI</h6>
							<?php
								$code = array();
								$code[] = 'GET '. base_url('/api/register_custom_field');
								echo ___h($code,'html');
							?>

							<h6 class="mt-3">Example Response</h6>
							<?php
								$code = array();
								$code[] = '{';
								$code[] = '    "fields": [';
								$code[] = '        {';
								$code[] = '            "type": "select",';
								$code[] = '            "required": false,';
								$code[] = '            "label": "Select",';
								$code[] = '            "className": "form-control",';
								$code[] = '            "name": "custom_select-1594271473044",';
								$code[] = '            "min": "",';
								$code[] = '            "max": "",';
								$code[] = '            "maxlength": "",';
								$code[] = '            "values": [';
								$code[] = '                {';
								$code[] = '                    "label": "Option 1",';
								$code[] = '                    "value": "option-1",';
								$code[] = '                    "selected": "true"';
								$code[] = '                },';
								$code[] = '                {';
								$code[] = '                    "label": "Option 2",';
								$code[] = '                    "value": "option-2"';
								$code[] = '                },';
								$code[] = '                {';
								$code[] = '                    "label": "Option 3",';
								$code[] = '                    "value": "option-3"';
								$code[] = '                }';
								$code[] = '            ],';
								$code[] = '            "mobile_validation": false';
								$code[] = '        },';
								$code[] = '        {';
								$code[] = '            "type": "text",';
								$code[] = '            "required": true,';
								$code[] = '            "label": "Custom FIeld",';
								$code[] = '            "className": "form-control",';
								$code[] = '            "name": "custom_text-1594269069679",';
								$code[] = '            "min": "",';
								$code[] = '            "max": "",';
								$code[] = '            "maxlength": "",';
								$code[] = '            "values": null,';
								$code[] = '            "mobile_validation": false';
								$code[] = '        }';
								$code[] = '    ]';
								$code[] = '}';
								echo ___h($code,'javascript');
							?>



							<h5 class="mt-5">Create a Affiliate Registration</h5>
							<hr>
							<p>This API is used to create a new Affiliate User and a Affiliate Registration in a single request. This is useful if for example you have a main website that User’s create their account on initially. The User is technically creating their global User object and a User Registration for that website (i.e. that Affiliate Script). In this case, you will want to create the Affiliate and the Affiliate Registration in a single step. This is the API to use for that.</p>


							<h6>URI</h6>
							<?php
								$code = array();
								$code[] = 'POST '. base_url('/api/register');
								echo ___h($code,'html');
							?>

							<h6 class="mt-5">Request Body</h6>
							<table class="table-inverse table">
								<thead>
									<tr>
										<th width="200px">Field</th>
										<th width="100px">Type</th>
										<th>Description</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th>firstname</th>
										<th>string</th>
										<td>The first name of the User.</td>
									</tr>

									<tr>
										<th>lastname</th>
										<th>string</th>
										<td>The User’s last name.</td>
									</tr>

									<tr>
										<th>email</th>
										<th>email</th>
										<td>The User’s email address. An email address is a unique in Affiliate Pro and stored in lower case.</td>
									</tr>

									<tr>
										<th>username</th>
										<th>string</th>
										<td>The username of the User for this Application only.</td>
									</tr>

									<tr>
										<th>password</th>
										<th>string</th>
										<td>The User’s plain texts password. This password will be hashed and the provided value will never be stored and cannot be retrieved.</td>
									</tr>
									<?php 
										foreach ($customField as $key => $value) { 
											if($value['type'] == 'header') continue; 
									?>
										<tr>
											<th><?= $value['name'] ?></th>
											<th>custom field</th>
											<td>The Custom Fields <b><?= $value['label'] ?></b></td>
										</tr>
									<?php } ?>

									<tr>
										<th>terms</th>
										<th>boolean</th>
										<td>Accept Terms & Condition</td>
									</tr>
								</tbody>
							</table>


							<h6 class="mt-5">Example Request JSON</h6>

							<?php
								$code = array();
								$code[] = "{";
								$code[] = "	'firstname':'Keri',";
								$code[] = "	'lastname':'Taylor',";
								$code[] = "	'username':'taylor.keri',";
								$code[] = "	'email':'taylor.keri@gmail.com',";
								$code[] = "	'password':'password',";
								$code[] = "	'terms':'true',";
								$code[] = "}";
								echo ___h($code,'javascript');
							?>

							<h6 class="mt-5">Response</h6>
							<p>The response for this API contains the User and the User Registration that were created. Security sensitive fields will not be returned in the response.</p>

							<b class="mt-4">errors</b>
							<p>Error object return the all error in object key is a field name and value is error title</p>

							<b class="mt-4">success</b>
							<p>If user created successfully them success message will be returned..</p>

						</div>
					<?php } ?>

					<?php if($module_key == 'wp_forms'){ ?>
						<h2>WPFroms Plugin Integration</h2>
						<div>
							<ol class="installed-step">
								<li>Log into your WordPress dashboard.</li>
								<li>Go To "plugins" page on WordPress left menu.</li>
								<li>Install new plugin "Header and Footer Scripts" from wordpres store and activate it.</li>
								<li>Create a Thank you page using wordpress post/page.</li>
								<li>Go to WPForms plugin>>Edit option>>Setting>>Confirmation and choose your Confirmation page, The "Thank you" page you did in step 4.</li>
								<li>Create your banner in affiliate admin side.</li>
								<li>AFter finish step 6, so copy code of your action to your "Thank you". Example here: 
									<!-- Trigger the modal with a button -->
									<button class="btn_info" data-toggle="modal" data-target="#myModal"><i class="fa fa-info"></i></button>
								</li>
								<li>Thats it! you just finish your "WPFforms" Integration with Affiliate Pro script.</li>
							</ol>

							<?php
							$code = array();
							$code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
							$code[] = '<script type="text/javascript">';
							$code[] = '	AffTracker.setWebsiteUrl( "WebsiteUrl" );';
							$code[] = '	AffTracker.createAction( "actionCode" )';
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

							<h6>Avilabel Action Code is here</h6>
							<ul>
								<?php foreach ($action_codes as $key => $value) { ?>
									<li> <?= $value['action_code'] ?> </li>
								<?php } ?>
							</ul>
						</div>
					<?php } ?>
					
					<?php if($module_key == 'woocommerce'){ ?>
						<p>Integrate affiliate script into WooCommerce. download WooCommerce module from here <a href="<?= base_url('integration/download_plugin/woocommerce') ?>">WordPress Module</a> and follow following step.</p>
						<hr>

						<ol class="installed-step">
							<li>Log into your WordPress dashboard.</li>
							<li>Go To "plugins" page on WordPress left menu.</li>
							<li>Upload new plugin zip file that you download from Affiliate script.</li>
							<li>Install the plugin and wait until installation will be finish.</li>
							<li>Activate Plugin, and now you completed "Affiliate" plugin installation successfully.</li>
						</ol>
					<?php } ?>

					<?php if($module_key == 'wp_show_affiliate_id'){ ?>
						<p>Integrate show affiliate plugin into wordpress. download module from here <a href="<?= base_url('integration/download_plugin/show_affiliate_id') ?>">WordPress Module</a> and follow following step.</p>
						<hr>

						<ol class="installed-step">
							<li>Log into your WordPress dashboard.</li>
							<li>Go To "plugins" page on WordPress left menu.</li>
							<li>Upload new plugin zip file that you download from Affiliate script.</li>
							<li>Install the plugin and wait until installation will be finish.</li>
							<li>Activate Plugin, and now you completed "Show Affiliate ID" plugin installation successfully.</li>
						</ol>
					<?php } ?>

					<?php if($module_key == 'show_affiliate_id'){ ?>
						<p>If you want to show affiliate id in your external website then you need to add this script code in to your website inside head tag </p>
						<hr>


						<?php
							$code = array();
							$code[] = '<script type="text/javascript" src="'. base_url('integration/show_affiliate_id') .'"></script>';
							$code[] = '<script type="text/javascript">';
							$code[] = '	var af_df_setting = {';
							$code[] = '	  position:\'bottom\',';
							$code[] = '	  text:\'Affiliate ID is {id}\',';
							$code[] = '	}';
							$code[] = '</script>';
							echo ___h($code,'html');
						?>

						<br>
						<b>Where:</b>
						<ul>
							<li><strong>position</strong> = default is bottom
								<ol>
									<li>bottom </li>
									<li>top</li>
									<li>left</li>
									<li>right</li>
									<li>top-left</li>
									<li>top-right</li>
									<li>bottom-left</li>
									<li>bottom-right</li>
								</ol>
							</li>
							<li><strong>text</strong> = You can customize text line. use {id} where to place affiliate id</li>
						</ul> 
						
					<?php } ?>

					<?php if($module_key == 'postback'){ ?>
						<p>For JavaScript, iFrame, and image pixels, there is always the risk that a conversion is lost due to the session cookie being blocked or deleted from the user's browser. While these instances are rare, (around 3% of internet users block cookies) to ensure that tracking is as accurate as it can be, you can implement a cookieless tracking backup option where you can manually pass the session clickid to the advertiser landing page, and on conversion, advertiser can pass the clickid back to the offer pixel to record conversions in instances where cookies are not being tracked.</p>

						<p>In this scheme advertiser has to catch and store clickid parameter that SLICE Digital pass it to advertisers web-site. SLICE Digital transfer the visitor/customer to advertisers web-site onto the following URL (the example):</p>

						<b>Advertisers web-site destination (sample)</b>

						<?php
							$code = array();
							$code[] = 'https://www.domain.co.nz/?city={city}&regionCode={regionCode}&regionName={regionName}&countryCode={countryCode}&countryName={countryName}&continentName={continenName}&timezone={timezone}&currencyCode={currencyCode}&currencySymbol={currencySymbol}&ip={ip}&type={type}&id={id}&custom_field1={custom_field1}&custom_field2={custom_field2}';
							echo ___h($code,'html');
						?>

						<br>
						<b>Where:</b>
						<ul>
							<li>{city} - City Name</li>
							<li>{regionCode} - Region Code</li>
							<li>{regionName} - Region Name</li>
							<li>{countryCode} - Country Code</li>
							<li>{countryName} - Country Name</li>
							<li>{continentName} - Continent Name</li>
							<li>{timezone} - Timezone</li>
							<li>{currencyCode} - Currency Code</li>
							<li>{currencySymbol} - Currency Symbol</li>
							<li>{ip} - IP-address</li>
							<li>{type} - Type (action, general_click, product_click, sale) </li>
							<li>{id} - if its type=sale than saleid_param_info otherwise its clickid_param_info</li>
							<li>{custom_field1} - Custom Field 1</li>
							<li>{custom_field2} - Custom Field 2</li>
						</ul>

						<br>
						
						<b>Destination example:</b>
						<p>Advertisers web-site destination (example)</p>
						<?php
							$code = array();
							$code[] = 'https://www.domain.co.nz/?city=New York&regionCode=NY&regionName=New York&countryCode=US&countryName=United States&continentName=NA&timezone=North America&currencyCode=$&currencySymbol=USD&ip=170.171.1.24&type=general_click&id=1542';
							echo ___h($code,'html');
						?>

					<?php } ?>

					<?php if($module_key == 'wp_user_register'){ ?>
						<p>WordPress/Woocommerce Registration Bridge. download the plugin from here
							<a href="<?= base_url('integration/download_plugin/wp_user_register') ?>">Download Plugin</a> and follow the following steps.</p>
							<hr>

							<ol class="installed-step">
								<li>Download the WordPress plugin from this page, you can find it on the title.</li>
								<li>Log into your WordPress dashboard.</li>
								<li>Go To "plugins" page on WordPress left menu.</li>
								<li>Upload new plugin zip file that you download from Affiliate script.</li>
								<li>Install the plugin and wait until the installation will be finished.</li>
								<li>Activate Plugin, and now you completed "Affiliate" plugin installation successfully.</li>
								<li>Go to the WordPress menu, choose the setting on WordPress menu, and you will see plugin name, press on it to edit. 
									<button class="btn_info" data-toggle="modal" data-target="#myModal_bridge"><i class="fa fa-info"></i></button></li>
									<li>You can set register only wordpress regular registration users or Register only Woocommerce user or both.</li>
								</ol>
							<?php } ?>

							<?php if($module_key == 'bigcommerce'){ ?>
								
								<p>Integrate affiliate script into Big Commerce. Install our "Affiliate Pro" module in to your store
									<hr>

									<ol class="installed-step">
										<li>Log into your Big Commerce dashboard.</li>
										<li>From The Left Side Panel Open <code class="code_">Store Front -> Script Manager</code></li>
										<li>
											Create a new Script

											<ol class="installed-step">
												<div class="step"><b>Name of script : </b>  Affiliate Script </div>
												<div class="step"><b>Description : </b>  Affiliate Tracking Code </div>
												<div class="step"><b>Location on page : </b>  footer </div>
												<div class="step"><b>Select pages where script will be added : </b> All pages </div>
												<div class="step"><b>Script type : </b> Script </div>
												<div class="step"><b>Script contents : </b> </div>

												<?php
												$code = array();
												$code[] = '<script type="text/javascript">';
												$code[] = '	if("{{ page_type }}" == "product"){';
												$code[] = '		{{ inject "data" product }}';
												$code[] = '		var productData = JSON.parse({{jsContext}});';
												$code[] = '		AffTracker.setWebsiteUrl(window.location.hostname);';
												$code[] = '		AffTracker.productClick( productData["data"]["id"] );';
												$code[] = '	}';
												$code[] = '	';
												$code[] = '	if("{{ page_type }}" == "orderconfirmation"){';
												$code[] = '		fetch("/api/storefront/order/{{checkout.order.id}}", {credentials: "include"})';
												$code[] = '		.then(function(response) {';
												$code[] = '			return response.json();';
												$code[] = '		})';
												$code[] = '		.then(function(orderDetails) {';
												$code[] = '			var product_ids = "";';
												$code[] = '			orderDetails.lineItems.physicalItems.forEach(function(j){';
												$code[] = '			 	product_ids += product_ids ? "," + j["productId"] : j["productId"]';
												$code[] = '			})';
												$code[] = '			AffTracker.setWebsiteUrl(window.location.hostname);';
												$code[] = '			AffTracker.add_order({';
												$code[] = '				order_id       : "{{checkout.order.id}}",';
												$code[] = '				order_currency : orderDetails.currency.code,';
												$code[] = '				order_total    : orderDetails.orderAmount,';
												$code[] = '				product_ids    : product_ids,';
												$code[] = '			})';
												$code[] = '		});';
												$code[] = '	}';
												$code[] = '</script>';
												
												echo ___h($code,'html');
												?>
											</ol>
										</li>

										<li>
											Create a new Script

											<ol class="installed-step">
												<div class="step"><b>Name of script : </b>  Affiliate Script </div>
												<div class="step"><b>Description : </b>  Affiliate Tracking Link </div>
												<div class="step"><b>Location on page : </b>  Head </div>
												<div class="step"><b>Select pages where script will be added : </b> All pages </div>
												<div class="step"><b>Script type : </b> URL </div>
												<div class="step"><b>Load method : </b> Default </div>
												<div class="step"><b>Script URL : </b> </div>

												<?php
												$code = array();
												$code[] = $base_url .'bigcommerce.js';
												echo ___h($code,'html');
												?>
											</ol>
										</li>
										<li>congratulations you have successfully installed Affiliate Pro</li>
									</ol>
								<?php } ?>

								<?php if($module_key == 'prestashop'){ ?>
									<p>Integrate affiliate script into prestashop. download prestashop module from here <a href="<?= base_url('integration/download_plugin/prestashop') ?>">Prestashop Module</a> and follow following step.</p>
									
									<ol class="installed-step">
										<li>Log into your PrestaShop dashboard.</li>
										<li>Using the left menu bar, Open the Modules tab and select the "Modules and Services" option</li>
										<li>From here you will see the the normal list of the available modules for your store. To upload your third party module, look to the upper right corner of the screen and click on the Add New Module button. </li>
										<li>sing the Browse button, locate the module from your local computer. Once selected, click the Upload This Module button underneath the Module File field. This will upload the module to your PrestaShop Module API. Once you see the successful message, you know the module is added correctly.</li>
										<li>Continue the installation by scrolling down the modules list until you find the one you installed. We installed the "Affiliate Pro" module. Once you find the module, click on the Install button located to the right side of the module row.</li>
										<li>Once the module runs its install program, you should see a message indicating it was completed.</li>
										<li>You have now completed a "Affiliate Pro" module install. </li>
									</ol>
								<?php } ?>

								<?php if($module_key == 'xcart'){ ?>
									<p>Integrate affiliate script into Xcart. get backup of your website and follow following step.</p>

									<ol class="installed-step">
										<li>
											Open file <code class="code_">/skins/customer/header/parts/script_config.twig</code> and add following code at the end of file

											<?php
												$code = array();
												$code[] = '<script type="text/javascript" src="'. base_url('integration/xcart') .'"></script>';
												echo ___h($code,'html');
											?>
											
										</li>
										<li>
											Open file <code class="code_">/classes/XLite/Controller/Customer/Product.php</code> and add following code before the <code class="code_">parent::handleRequest();</code> line
											
											<?php
											$code = array();
											$code[] = '/* AFFILIATE PRO integration */';
											$code[] = '	$ipaddress = "";';
											$code[] = '	if (getenv("HTTP_CLIENT_IP")) $ipaddress           = getenv("HTTP_CLIENT_IP");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED")) $ipaddress     = getenv("HTTP_X_FORWARDED");';
											$code[] = '	else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress   = getenv("HTTP_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_FORWARDED")) $ipaddress       = getenv("HTTP_FORWARDED");';
											$code[] = '	else if(getenv("REMOTE_ADDR")) $ipaddress          = getenv("REMOTE_ADDR");';
											$code[] = '	else $ipaddress                                    = "UNKNOWN";';
											$code[] = '	$affliate_cookie = (isset($_GET["af_id"]) ? $_GET["af_id"] : (isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : "") ); ';
											$code[] = '	$protocol = ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http");';
											$code[] = '	$base_url = $protocol . "://" . $_SERVER["HTTP_HOST"];';
											$code[] = '	$complete_url =   $base_url . $_SERVER["REQUEST_URI"];';
											$code[] = '	$affiliateData = array(';
											$code[] = '	    "product_id"       => $this->getProduct()->getId(),';
											$code[] = '	    "af_id"            => $affliate_cookie,';
											$code[] = '	    "ip"               => $ipaddress,';
											$code[] = '	    "base_url"         => base64_encode($base_url),';
											$code[] = '	    "script_name"      => "xcart",';
											$code[] = '	    "current_page_url" => base64_encode($complete_url),';
											$code[] = '	);';
											$code[] = '	$context_options = stream_context_create(array(';
											$code[] = '	    "http"=>array(';
											$code[] = '	        "method"=>"GET",';
											$code[] = '	        "header"=> "User-Agent: ". (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : ""),';
											$code[] = '	    )';
											$code[] = '	)); ';
											$code[] = '	file_get_contents("'. base_url('integration/addClick') .'?".http_build_query($affiliateData), false, $context_options);';
											$code[] = '/* end of AFFILIATE PRO integration */';
											
											echo ___h($code,'php');
											?>
											
										</li>
										<li>
											Open file <code class="code_">/classes/XLite/Controller/Customer/CheckoutSuccess.php</code> add following code before <code class="code_">parent::handleRequest();</code> line
											
											<?php
											$code = array();
											$code[] = '/* AFFILIATE PRO integration */';
											$code[] = '    $ipaddress = "";';
											$code[] = '';
											$code[] = '    if (getenv("HTTP_CLIENT_IP")) $ipaddress = getenv("HTTP_CLIENT_IP");';
											$code[] = '    else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");';
											$code[] = '    else if(getenv("HTTP_X_FORWARDED")) $ipaddress = getenv("HTTP_X_FORWARDED");';
											$code[] = '    else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress = getenv("HTTP_FORWARDED_FOR");';
											$code[] = '    else if(getenv("HTTP_FORWARDED")) $ipaddress = getenv("HTTP_FORWARDED");';
											$code[] = '    else if(getenv("REMOTE_ADDR")) $ipaddress = getenv("REMOTE_ADDR");';
											$code[] = '    else $ipaddress = "UNKNOWN";';
											$code[] = '';
											$code[] = '    $protocol = ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http");';
											$code[] = '    $base_url = $protocol . "://" . $_SERVER["HTTP_HOST"];';
											$code[] = '    $affliate_cookie = (isset($_GET["af_id"]) ? $_GET["af_id"] : (isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : "") ); ';
											$code[] = '';
											$code[] = '    $affiliateData = array(';
											$code[] = '        "order_id"       => $this->getOrder()->getOrderNumber(),';
											$code[] = '        "order_currency" => $this->getOrder()->getCurrency()->getCurrencySymbol(false),';
											$code[] = '        "order_total"    => $this->getOrder()->getPaidTotal(),';
											$code[] = '        "product_ids"    => array(),';
											$code[] = '        "af_id"          => $affliate_cookie,';
											$code[] = '        "ip"             => $ipaddress,';
											$code[] = '        "base_url"       => base64_encode($base_url),';
											$code[] = '        "script_name"    => "xcart",';
											$code[] = '    );';
											$code[] = '';
											$code[] = '    foreach ($this->getOrder()->getItems() as $item) { $affiliateData["product_ids"][] = $item->getItemId(); }';
											$code[] = '';
											$code[] = '    $context_options = stream_context_create(array(';
											$code[] = '        "http" => array(';
											$code[] = '          "method" => "GET",';
											$code[] = '          "header" => "User-Agent: ". (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : ""),';
											$code[] = '        )';
											$code[] = '    ));';
											$code[] = '';
											$code[] = '    file_get_contents("'. base_url('integration/addOrder') .'?".http_build_query($affiliateData), false, $context_options);';
											$code[] = '/* end of AFFILIATE PRO integration */';
											
											echo ___h($code,'php');
											?>
											
										</li>
										<li> Clear Files Cache
											<ol class="installed-step">
												<li>Goto Admin Dashboard</li>
												<li>Click on <b>System Tool</b> from left menu</li>
												<li>Click On <b>Cache Management</b> in System Tool Menu</li>
												<li>Click on Start Button in <b>Re-deploy the store</b> section</li>
											</ol>
										</li>
										<li>You have now completed a "Affiliate Pro" module install. </li>
									</ol>
								<?php } ?>

								<?php if($module_key == 'zencart'){ ?>
									<p>Integrate affiliate script into zen cart. get backup of your website and follow following step.</p>


									<div class="alert alert-info">
										For Find Your Template Directory Name Go <code class="code_">Admin>Tools>Template Selection</code> you can see your template directory name. use this name insted of <b>your_template_directory</b>
									</div>
									<ol class="installed-step">
										<li>
											Open file <code class="code_">/includes/templates/your_template_directory/common/html_header.php</code> and add following code at the end of file

											<?php
											$code = array();
											$code[] = '<script type="text/javascript" src="'. base_url('integration/zencart') .'"></script>';
											echo ___h($code,'html');
											?>
											
										</li>
										<li>
											Open file <code class="code_">/includes/templates/your_template_directory/templates/tpl_product_info_display.php</code> add following code after at the end of file
											
											<?php
											$code = array();
											$code[] = '<?php';
											$code[] = '	/* AFFILIATE PRO integration */';
											$code[] = '	$ipaddress = "";';
											$code[] = '	if (getenv("HTTP_CLIENT_IP")) $ipaddress           = getenv("HTTP_CLIENT_IP");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED")) $ipaddress     = getenv("HTTP_X_FORWARDED");';
											$code[] = '	else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress   = getenv("HTTP_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_FORWARDED")) $ipaddress       = getenv("HTTP_FORWARDED");';
											$code[] = '	else if(getenv("REMOTE_ADDR")) $ipaddress          = getenv("REMOTE_ADDR");';
											$code[] = '	else $ipaddress                                    = "UNKNOWN";';
											$code[] = '';
											$code[] = '	$affliate_cookie = (isset($_GET["af_id"]) ? $_GET["af_id"] : (isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : "") ); ';
											$code[] = '	$protocol = ((isset($_SERVER[\'HTTPS\']) && $_SERVER[\'HTTPS\'] == "on") ? "https" : "http");';
											$code[] = '	$base_url = $protocol . "://" . $_SERVER[\'HTTP_HOST\'];';
											$code[] = '	$complete_url =   $base_url . $_SERVER["REQUEST_URI"];';
											$code[] = '';
											$code[] = '	$affiliateData = array(';
											$code[] = '		"product_id"       => $products_id_current,';
											$code[] = '		"af_id"            => $affliate_cookie,';
											$code[] = '		"ip"               => $ipaddress,';
											$code[] = '		"base_url"         => base64_encode(HTTP_SERVER.DIR_WS_CATALOG),';
											$code[] = '		"script_name"      => "zencart",';
											$code[] = '		"current_page_url" => base64_encode($complete_url),';
											$code[] = '	);';
											$code[] = '';
											$code[] = '	$context_options = stream_context_create(array(';
											$code[] = '		"http"=>array(';
											$code[] = '			"method"=>"GET",';
											$code[] = '			"header"=> "User-Agent: ". (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : ""),';
											$code[] = '		)';
											$code[] = '	)); ';
											$code[] = '	';
											$code[] = '	file_get_contents("'. base_url('integration/addClick') .'?".http_build_query($affiliateData), false, $context_options);';
											$code[] = '	/* end of AFFILIATE PRO integration */';
											$code[] = '?>';
											echo ___h($code,'php');
											?>
											
										</li>
										<li>
											Open file <code class="code_">/includes/templates/your_template_directory/templates/tpl_checkout_success_default.php</code> add following code at the end of file

											<div class="alert alert-info">If you can't find file than search inside <b>template_default</b> folder</div>


											
											<?php
											$code = array();
											$code[] = '<?php';
											$code[] = '/* AFFILIATE PRO integration */';
											$code[] = '	$ipaddress = "";';
											$code[] = '	if (getenv("HTTP_CLIENT_IP")) $ipaddress = getenv("HTTP_CLIENT_IP");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED")) $ipaddress = getenv("HTTP_X_FORWARDED");';
											$code[] = '	else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress = getenv("HTTP_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_FORWARDED")) $ipaddress = getenv("HTTP_FORWARDED");';
											$code[] = '	else if(getenv("REMOTE_ADDR")) $ipaddress = getenv("REMOTE_ADDR");';
											$code[] = '	else $ipaddress = "UNKNOWN";';
											$code[] = '';
											$code[] = '	$protocol = ((isset($_SERVER[\'HTTPS\']) && $_SERVER[\'HTTPS\'] == "on") ? "https" : "http");';
											$code[] = '	$base_url = $protocol . "://" . $_SERVER[\'HTTP_HOST\'];';
											$code[] = '	$complete_url =   $base_url . $_SERVER["REQUEST_URI"];';
											$code[] = '';
											$code[] = '	$affliate_cookie = (isset($_GET["af_id"]) ? $_GET["af_id"] : (isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : "") ); ';
											$code[] = '';
											$code[] = '	$affiliateData = array(';
											$code[] = '		"order_id"       => $orders->fields[\'orders_id\'],';
											$code[] = '		"order_currency" => $order->info[\'currency\'],';
											$code[] = '		"order_total"    => $order->info[\'total\'],';
											$code[] = '		"product_ids"    => array(),';
											$code[] = '		"af_id"          => $affliate_cookie,';
											$code[] = '		"ip"             => $ipaddress,';
											$code[] = '		"base_url"       => base64_encode($base_url),';
											$code[] = '		"script_name"    => "zencart",';
											$code[] = '	);';
											$code[] = '';
											$code[] = '	foreach ($order->products as $item) { $affiliateData["product_ids"][] = $item["id"]; }';
											$code[] = '	';
											$code[] = '    $context_options = stream_context_create(array(';
											$code[] = '        "http" => array(';
											$code[] = '          "method" => "GET",';
											$code[] = '          "header" => "User-Agent: ". (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : ""),';
											$code[] = '        )';
											$code[] = '    ));';
											$code[] = '	';
											$code[] = '    file_get_contents("'. base_url('integration/addOrder') .'?".http_build_query($affiliateData), false, $context_options);';
											$code[] = '/* end of AFFILIATE PRO integration */';
											$code[] = '?>';
											echo ___h($code,'php');
											?>
											
										</li>
										<li>You have now completed a "Affiliate Pro" module install. </li>
									</ol>
								<?php } ?>

								<?php if($module_key == 'oscommerce'){ ?>
									<p>Integrate affiliate script into oscommerce. get backup of your website and follow following step.</p>

									<ol class="installed-step">
										<li>
											Open file <code class="code_">/includes/template_top.php</code> and add following code at the end of file
											
											<?php
											$code = array();
											$code[] = '<script type="text/javascript" src="'. base_url('integration/oscommerce') .'"></script>';
											echo ___h($code,'html');
											?>
											
										</li>
										<li>
											Open file <code class="code_">product_info.php</code> and add following code after <code class="code_">$product_info = tep_db_fetch_array($product_info_query);</code> this line (around 42 line)
											
											<?php
											$code = array();
											$code[] = '/* AFFILIATE PRO integration */';
											$code[] = '	$ipaddress = "";';
											$code[] = '	if (getenv("HTTP_CLIENT_IP")) $ipaddress           = getenv("HTTP_CLIENT_IP");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_X_FORWARDED")) $ipaddress     = getenv("HTTP_X_FORWARDED");';
											$code[] = '	else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress   = getenv("HTTP_FORWARDED_FOR");';
											$code[] = '	else if(getenv("HTTP_FORWARDED")) $ipaddress       = getenv("HTTP_FORWARDED");';
											$code[] = '	else if(getenv("REMOTE_ADDR")) $ipaddress          = getenv("REMOTE_ADDR");';
											$code[] = '	else $ipaddress                                    = "UNKNOWN";';
											$code[] = '	';
											$code[] = '	$affliate_cookie = (isset($_GET["af_id"]) ? $_GET["af_id"] : (isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : "") ); ';
											$code[] = '	$current_url = tep_href_link(FILENAME_PRODUCT_INFO, "products_id=" . $product_info["products_id"]);';
											$code[] = '	';
											$code[] = '	$affiliateData = array(';
											$code[] = '		"product_id"       => $product_info["product_id"],';
											$code[] = '		"af_id"            => $affliate_cookie,';
											$code[] = '		"ip"               => $ipaddress,';
											$code[] = '		"base_url"         => base64_encode(tep_href_link(FILENAME_DEFAULT)),';
											$code[] = '		"script_name"      => "oscommerce",';
											$code[] = '		"current_page_url" => base64_encode($current_url),';
											$code[] = '	);';
											$code[] = '	';
											$code[] = '	$context_options = stream_context_create(array(';
											$code[] = '		"http"=>array(';
											$code[] = '			"method"=>"GET",';
											$code[] = '			"header"=> "User-Agent: ". (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : ""),';
											$code[] = '		)';
											$code[] = '	)); ';
											$code[] = '	';
											$code[] = '	file_get_contents("'. base_url('integration/addClick') .'?".http_build_query($affiliateData), false, $context_options);';
											$code[] = '/* end of AFFILIATE PRO integration */';
											echo ___h($code,'php');
											?>
											
										</li>
										<li>
											Open file <code class="code_">checkout_success.php</code> and add following code after <code class="code_">$orders = tep_db_fetch_array($orders_query);</code> this line (around 27 line)
											
											<?php
											$code = array();
											$code[] = '/* AFFILIATE PRO integration */';
											$code[] = '    require(DIR_WS_CLASSES . "order.php");';
											$code[] = '    $_order = new order($orders["orders_id"]);';
											$code[] = '	';
											$code[] = '    $ipaddress = "";';
											$code[] = '    if (getenv("HTTP_CLIENT_IP")) $ipaddress = getenv("HTTP_CLIENT_IP");';
											$code[] = '    else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");';
											$code[] = '    else if(getenv("HTTP_X_FORWARDED")) $ipaddress = getenv("HTTP_X_FORWARDED");';
											$code[] = '    else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress = getenv("HTTP_FORWARDED_FOR");';
											$code[] = '    else if(getenv("HTTP_FORWARDED")) $ipaddress = getenv("HTTP_FORWARDED");';
											$code[] = '    else if(getenv("REMOTE_ADDR")) $ipaddress = getenv("REMOTE_ADDR");';
											$code[] = '    else $ipaddress = "UNKNOWN";';
											$code[] = '	';
											$code[] = '    $affliate_cookie = (isset($_GET["af_id"]) ? $_GET["af_id"] : (isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : "") ); ';
											$code[] = '    $affiliateData = array(';
											$code[] = '      "order_id"       => $orders["orders_id"],';
											$code[] = '      "order_currency" => $_order->info["currency"],';
											$code[] = '      "order_total"    => preg_replace(\'/[^\d\.]/\', "", $_order->info["total"]),';
											$code[] = '      "product_ids"    => array(),';
											$code[] = '      "af_id"          => $affliate_cookie,';
											$code[] = '      "ip"             => $ipaddress,';
											$code[] = '      "base_url"       => base64_encode(tep_href_link(FILENAME_DEFAULT)),';
											$code[] = '      "script_name"    => "oscommerce",';
											$code[] = '    );';
											$code[] = '	';
											$code[] = '    foreach ($_order->products as $item) { $affiliateData["product_ids"][] = $item["id"]; }';
											$code[] = '	';
											$code[] = '    $context_options = stream_context_create(array(';
											$code[] = '        "http" => array(';
											$code[] = '          "method" => "GET",';
											$code[] = '          "header" => "User-Agent: ". (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : ""),';
											$code[] = '        )';
											$code[] = '    ));';
											$code[] = '	';
											$code[] = ' file_get_contents("'. base_url('integration/addOrder') .'?".http_build_query($affiliateData), false, $context_options);';
											$code[] = '/* end of AFFILIATE PRO integration */';
											echo ___h($code,'php');
											?>
											
										</li>
										<li>You have now completed a "Affiliate Pro" module install. </li>
									</ol>
								<?php } ?>

								<?php if($module_key == 'paypal'){ ?>
									<p>PayPal Express Checkout integrates using IPN callback even if the callback is used by other system (e.g. shopping cart).</p>
									
									<ol class="installed-step">
										<li>
											PayPal button
											<ol>
												<li>Now add the following code into EVERY PayPal button form</li>
												<li>
													<?php
													$code = array();
													$code[] = '<input type="hidden" name="custom" value="custom=your_custom_value_here&af_id=<?= $_COOKIE[\'af_id\'] ?>" />';
													echo ___h($code,'html');
													?>
													
												</li>
											</ol>
										</li>
										<li>
											Integration 
											<ol>
												<li>Now the IPN callback is pointed to your script. This callback has to be forwarded also to PAP script, In case, your paypal processing script is in PHP, you can use following code to accomplish that. You can place it at the beginning of your processing file.</li>
												<li>
													<?php
													$code = array();

													$code[] = '/* AFFILIATE PRO integration */';
													$code[] = '	parse_str($_POST["custom"],$_CUSTOM);';
													$code[] = '	$_POST["custom"] = $_CUSTOM["custom"];';
													$code[] = '	$ch = curl_init();';
													$code[] = '	curl_setopt($ch, CURLOPT_URL, "'. base_url('integration/addOrderPaypal') .'");';
													$code[] = '	curl_setopt($ch, CURLOPT_POST, 1);';
													$code[] = '	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);';
													$code[] = '	curl_setopt($ch, CURLOPT_POSTFIELDS, array(';
													$code[] = '		"post"           => json_encode($_POST),';
													$code[] = '		"af_id"          => $_CUSTOM["af_id"],';
													$code[] = '		"order_id"       => "YOUR_ORDER_ID",';
													$code[] = '		"product_ids"    => "PRODUCTS_ID",';
													$code[] = '		"base_url"       => base64_encode("YOUR_WEBSITE_URL"),';
													$code[] = '	));';
													$code[] = '	curl_exec($ch);';
													$code[] = '/* end of AFFILIATE PRO integration */';

													echo ___h($code,'php');
													?>
													
													<p>
														<h6>All possible tracking parameters</h6>
														<div class="well">
															<strong>YOUR_WEBSITE_URL</strong> : Website root URL <br>
															<strong>YOUR_ORDER_ID</strong>    : Unique Order ID <br>
															<strong>PRODUCTS_ID</strong>      : product ids of order, comma separated string <br>
														</div>
													</p>
												</li>
											</ol>
										</li>
										<li>You have now completed a "Affiliate Pro" module install. </li>
									</ol>
								<?php } ?>

								<?php if($module_key == 'magento'){ ?>
									<div role="tabpanel">
										<!-- Nav tabs -->
										<ul class="nav nav-pills" role="tablist">
											<li role="presentation" class="nav-item">
												<a href="#magento-1" class="nav-link active" aria-controls="magento-1" role="tab" data-toggle="tab">Magento 1</a>
											</li>
											<li role="presentation" class="nav-item">
												<a href="#magento2" class="nav-link" aria-controls="magento2" role="tab" data-toggle="tab">Magento 2</a>
											</li>
										</ul>
										
										<br>
										<div class="tab-content">
											<div role="tabpanel" class="tab-pane active" id="magento-1">
												<p>Integrate affiliate script into magento 1. download magento module from here <a href="<?= base_url('integration/download_plugin/magento/1') ?>">Magento Module</a> and follow following step. or check <a target='_blank' href="https://docs.mageplaza.com/kb/installation.html">Official document</a></p>
												
												<ol class="installed-step">
													<li>Extract download zip file</li>
													<li>Upload "app" folder to root folder of your magento store</li>
													<li>Check that you have a current backup of your site or create one by going into  <code class="code_">system->tools->backup</code>. This will be useful in case anything goes wrong</li>
													<li>Disable compilations via  <code class="code_">system->tools->Compilations</code></li>
													<li>Clear cache by going into  <code class="code_">System->Cache Management</code>, selecting all the files in the list, choosing the refresh option in the dropdown menu, and finally clicking Submit.</li>
													<li>
														Activate the extension  <code class="code_">System-> Configuration </code> 
														<p>click on <b>Advanced</b> menu from left panel</p>
														<p>Find <b>AffiliatePro_Magento1</b> and enable it</p>
													</li>
												</ol>
											</div>
											<div role="tabpanel" class="tab-pane" id="magento2">
												<p>Integrate affiliate script into magento. download magento module from here <a href="<?= base_url('integration/download_plugin/magento') ?>">Magento Module</a> and follow following step. or check <a target='_blank' href="https://docs.mageplaza.com/kb/installation.html">Official document</a></p>
												
												<ol class="installed-step">
													<li>Extract download zip file</li>
													<li>Upload "AffiliatePro" folder to <code class="code_">/app/code/</code> folder in your magento store</li>
													<li>
														<b> Run Command using php</b>
														<ul>
															<li>Create <code class="code_">cmd.php</code> file into magento root folder </li>
															<li>
																Add following content to cmd.php file

																<?php
																$code = array();
																$code[] = '<?php';
																$code[] = '	exec("php bin/magento setup:upgrade",$o);';
																$code[] = '	exec("php bin/magento setup:static-content:deploy",$o);';
																$code[] = '	echo "Module installed successfully";';
																
																echo ___h($code,'php');
																?>
															</li>
															<li>
																Open cmd.php file into browser using following url
																<code class="code_">http://url_of_magento_store/cmd.php</code>
															</li>
														</ul>
													</li>
												</ol>
											</div>
										</div>
									</div>
								<?php } ?>

								<?php if($module_key == 'opencart'){ ?>
									<p>Integrate affiliate script into opencart. download opencart extentsion from below links and follow following step.</p>

									<br>
									<table class="ml-4">
										<tr>
											<td>For Opencart Version 1564 To 2200 </td>
											<td><a href="<?= base_url('integration/download_plugin/opencart/1') ?>">Download</a></td>
										</tr>
										<tr>
											<td>For Opencart Version 2300 To 3011 </td>
											<td><a href="<?= base_url('integration/download_plugin/opencart/2') ?>">Download</a></td>
										</tr>
									</table>

									<br>
									
									<ol class="installed-step">
										<li>Lets start by logging into your store admin panel. Navigate to <code class="code_">Extensions > Extension installer</code></li>
										<li>Click on the upload button. A dialog box should open.</li>
										<li>Locate the installation zip file of the extension you are going to install and select it.</li>
										<li>After clicking “OK” your extension will be uploaded and a “success” message should appear.</li>
										<li>Now your module should be visible in <code class="code_">Extensions > Modules</code>. After locating it in the Module list just click the install button (“ + ” sign).</li>
										<li>The final step of the installation process is to apply the changes we have just made. In order to do so, go to <code class="code_">Extensions > Modifications</code> and click the Refresh sign at the upper right corner of the page.</li>
									</ol>
								<?php } ?>

								
								<?php if(in_array($module_key, array('general_integration','laravel','codeigniter','cackphp'))){ ?>
									<h2>Common Tracking Script</h2>
									<div>
										<p>Add following script to all pages of your website. include in common file like header or footer</p>
										<?php
										$code = array();
										$code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
										echo ___h($code,'html');
										?>
									</div>
									<br><hr>
									
									<h2>General Click Tracking</h2>
									<div>
										<p>
											Use Following code to track genreal clicks of website.
										</p>

										
										<?php
										$code = array();
										$code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
										$code[] = '<script type="text/javascript">';
										$code[] = '	AffTracker.setWebsiteUrl( "WebsiteUrl" );';
										$code[] = '	AffTracker.generalClick( "general_code" );';
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

										<h6>Avilabel General Click Code is here</h6>
										<ul>
											<?php foreach ($general_codes as $key => $value) { ?>
												<li> <?= $value['general_code'] ?> </li>
											<?php } ?>
										</ul>

									</div>
									
									<br><hr>
									
									
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
										$code[] = '	AffTracker.setWebsiteUrl( "WebsiteUrl" );';
										$code[] = '	AffTracker.createAction( "actionCode" )';
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

										<h6>Avilabel Action Code is here</h6>
										<ul>
											<?php foreach ($action_codes as $key => $value) { ?>
												<li> <?= $value['action_code'] ?> </li>
											<?php } ?>
										</ul>
									</div>


									<br><hr>
									
									<h2>Order Tracking</h2>
									<div>
										<p>
											To track whole order,  add following code to your thank you page or order success page
										</p>

										<?php
										$code = array();
										$code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
										$code[] = '<script type="text/javascript">';
										$code[] = '	AffTracker.setWebsiteUrl( "WebsiteUrl" );';
										$code[] = '	AffTracker.add_order({';
										$code[] = '	    order_id 		: "OrderId",';
										$code[] = '	    order_currency 	: "OrderCurrency",';
										$code[] = '	    order_total		: "OrderTotal",';
										$code[] = '	    product_ids 	: "ProductIDs"';
										$code[] = '	})';
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
										$code[] = '	AffTracker.setWebsiteUrl( "WebsiteUrl" );';
										$code[] = '	AffTracker.add_order({';
										$code[] = '	    order_id 		: "<?php echo $variable_OrderId ?>",';
										$code[] = '	    order_currency 	: "<?php echo $variable_OrderCurrency ?>",';
										$code[] = '	    order_total		: "<?php echo $variable_OrderTotal ?>",';
										$code[] = '	    product_ids 	: "<?php echo $variable_ProductIDs ?>"';
										$code[] = '	})';
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
										$code[] = '	AffTracker.setWebsiteUrl( "WebsiteUrl" );';
										$code[] = '	AffTracker.stop_recurring("$variable_OrderId ")';
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



								<br><hr>
								<h2>Product Click Tracking</h2>
								<div>
									<p>
										Use Following code to your product details page so system can track click of products.
									</p>

									
									<?php
									$code = array();
									$code[] = '<script type="text/javascript" src="'. $base_url .'integration/general_integration"></script>';
									$code[] = '<script type="text/javascript">';
									$code[] = '	AffTracker.setWebsiteUrl( "website_url" );';
									$code[] = '	AffTracker.productClick( "ProductID" );';
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

						<?php if($module_key == 'shopify'){ ?>
							<p>Integrate affiliate script into shopify. follow following step.</p>

							<ol class="installed-step">
								<li>Login and goto shopify admin dashboard </code></li>
								<li>Goto  <code class="code_">Online Store -> Themes -> Current theme -> Action: Edit Code</code>
									<ul class="list-unstyled">
										<li>
											On left side in <b>Sections</b> Section Click on <b>header.liquid</b> file. and add following code on beginning of the file.

											<?php
											$code = array();
											$code[] = '<script type="text/javascript" src="'. $base_url .'integration/shopify"></script>';
											echo ___h($code,'html');
											?>
										</li>

										<li>
											On left side in <b>Templates</b> Section Click on <b>product.liquid</b> file. and add following code on beginning of the file.

											<?php
											$code = array();
											$code[] = '<script type="text/javascript" src="'. $base_url .'integration/shopify"></script>';
											$code[] = '<script type="text/javascript">';
											$code[] = '	AffTracker.setWebsiteUrl( "{{ shop.url }}" );';
											$code[] = '	AffTracker.productClick( "{{ product.id }}" );';
											$code[] = '</script>';
											
											echo ___h($code,'html');
											?>
										</li>
									</ul>
								</li>

								<li>
									Goto Setting (Bottom of left side) -> Click on Checkout -> in "Order processing" Section find "Additional scripts" box and add following code.

									<?php
									$code = array();
									$code[] = '<script type="text/javascript" src="'. $base_url .'integration/shopify"></script>';
									$code[] = '<script type="text/javascript">';
									$code[] = '	AffTracker.setWebsiteUrl( "{{ shop.url }}" );';
									$code[] = '	AffTracker.add_order({';
									$code[] = '	    order_id 		: "{{ order_number }}",';
									$code[] = '	    order_currency 	: "{{ shop.currency }}",';
									$code[] = '	    order_total		: "{{ total_price | money_without_currency }}",';
									$code[] = '	    product_ids 	: "{% for line_item in line_items %}{{ line_item.product_id }},{% endfor %}"';
									$code[] = '	})';
									$code[] = '</script>';
									echo ___h($code,'html');
									?>
								</li>
							</ol>
						<?php } ?>

					</div>
				</div>
			</div>
		</div>
	</div>


	
	<!-- Modal Info -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">WPForms Integration</h4>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				<div class="modal-body">
					<ul class="list-group">
						<li class="list-group-item">Setting a "Thank You" page on wordpress site and connect it to "WPForms" plugin.</br>
							<img class="zoom" src="<?php echo base_url(); ?>assets/guide_images/wpform1.png" alt="" style="width:100%;height:100%; margin-right:0; margin-left:0;">
						</li>
						<li class="list-group-item">Adding the integration code to the "Thank You" page.</br>
							<img class="zoom" src="<?php echo base_url(); ?>assets/guide_images/thank_you_page_code.png" alt="" style="width:100%;height:100%; margin-right:0; margin-left:0;">
						</li>
					</ul>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
			
		</div>
	</div>
	
	<!-- Modal bridge Info -->
	<div class="modal fade" id="myModal_bridge" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">WordPress/Woocommerce Bridge Plugin</h5>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				<div class="modal-body">
					<ul class="list-group">
						<li class="list-group-item">You can set register only wordpress regular registration users or Register only Woocommerce user or both.</br>
							<img class="zoom" src="<?php echo base_url(); ?>assets/guide_images/wp_bride_plugin.png" alt="" style="width:100%;height:100%; margin-right:0; margin-left:0;">
						</li>
					</ul>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
			
		</div>
	</div>