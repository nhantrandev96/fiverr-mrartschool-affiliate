<?php
error_reporting(0);

class IntegrationModel extends MY_Model{
 
	public function duplicate_tools($tools_id){
		// Find The Record from integration_tools table
		$tools = $this->db->query("SELECT * FROM integration_tools WHERE id=". (int)$tools_id)->row_array();
		// append duplicate keyword in name
		$tools['name'] = $tools['name'] ." - Duplicate";

		// update created date
		$tools['created_at'] = date("Y-m-d H:i:s");

		// remove primary key value. bcoz can't duplicate
		unset($tools['id']);

		// create new records & get new created id (primary key)
		$this->db->insert("integration_tools", $tools);
		$new_tool_id = $this->db->insert_id();


		// Tools record store in two table so we need to copy second table records also....
		// Find The Record from integration_tools_ads table by tools id Note Records can be more thana one
		$tools_ads = $this->db->query("SELECT * FROM integration_tools_ads WHERE tools_id=". (int)$tools_id)->result_array();
		
		foreach ($tools_ads as $key => $ads) {
			// remove primary key value. bcoz can't duplicate
			unset($ads['id']);

			// add new tools id
			$ads['tools_id'] = $new_tool_id;

			// copy images if banner
			if($ads['ads_type'] == 'banner'){
				$base_path = "assets/integration/uploads/{$tools_id}/";
				$new_base_path = "assets/integration/uploads/{$new_tool_id}/";
				if (!file_exists($new_base_path)) { mkdir($new_base_path, 0777, true); }

				$base_path .= $ads['value'];
				$new_base_path .= $ads['value'];
				copy($base_path, $new_base_path);
			}

			// create new records & get new created id (primary key)
			$this->db->insert("integration_tools_ads", $ads);
		}
	}

	public function stopRecurring($data){
		$o_order_id = (int)$data['order_id'];
		$domain_name = url_to_domain(base64_decode($data['base_url']));

		$order = $this->db->query("SELECT * FROM integration_orders WHERE order_id = {$o_order_id} AND base_url like '". $domain_name ."' ")->row_array();
		if($order){
			$wallets = $this->db->query("SELECT id FROM wallet WHERE type IN ('sale_commission','refer_sale_commission') AND comm_from = 'ex' AND reference_id_2 =". (int)$order['id'])->result_array();

			$wallet_ids = array_column($wallets, 'id');
			if($wallet_ids){
				$this->db->query("UPDATE wallet_recursion SET status=0 WHERE transaction_id IN (". implode(",", $wallet_ids) .") ");
			}

			echo "OK";
		} else {
			echo "ORDER NOT FOUND";
		}

		/*'type'           => 'sale_commission refer_sale_commission',
		'comm_from'    => 'ex',
		'reference_id_2' => $order_id,*/
	}

	public function addOrder($data){
		if(!isset($data['ip'])){ $data['ip'] = $_SERVER['REMOTE_ADDR']; }
		$tran_group_id = time();

		if(isset($data['af_id']) && $data['af_id'] && $data['ip']){
			//list($user_id,$ads_id) = explode("-", _encrypt_decrypt($data['af_id'],'decrypt'));
			list($user_id,$ads_id) = explode("-", _encrypt_decrypt(parse_affiliate_id($data['af_id']),'decrypt'));
			$user = $this->db->query("SELECT id FROM users WHERE type='user' AND id = ". (int)$user_id)->row();
		
			if($user){
				$o_order_id = (int)$data['order_id'];
				$domain_name = url_to_domain(base64_decode($data['base_url']));

				$orderAlready = $this->db->query("SELECT * FROM integration_orders WHERE order_id = {$o_order_id} AND script_name = '". $data['script_name'] ."' AND base_url like '". $domain_name ."' ")->num_rows();
				 
				if($orderAlready == 0){
					
					$currencys = $this->db->query("SELECT * FROM currency")->result_array();
					$currencys = array_column($currencys, 'code');

					if(isset($data['order_currency']) && in_array($data['order_currency'], $currencys) && $data['order_currency'] != 'USD'){
						$data['order_total'] = $this->currency->convert($data['order_total'], $data['order_currency'], 'USD');
					}

					$commissionData = $this->calcCommitions($data,$user_id,$ads_id);
					$orderData = array(
						'commission_type' => $commissionData['commission_type'],
						'commission'      => $commissionData['commission'],
						'order_id'        => $data['order_id'],
						'script_name'     => $data['script_name'],
						'user_id'         => $user_id,
						'total'           => (float)$data['order_total'],
						'ads_id'          => $commissionData['tools_id'],
						'currency'        => 'USD',
						'base_url'        => '',
						'ip'              => '',
						'country_code'    => '',
						'product_ids'     => '',
						'vendor_id'       => (int)$commissionData['vendor_id'],
					);

					if(isset($data['order_currency']) && in_array($data['order_currency'], $currencys)){
						$orderData['currency'] = $data['order_currency'];
					}

					if(isset($data['base_url'])){
						$orderData['base_url'] = $domain_name;
					}

					$orderData['ip'] = $data['ip'];
					if(isset($data['ip'])){
						$_ip = $this->Product_model->ip_info($data['ip']);
						$orderData['country_code'] = @$_ip['country_code'];
					}

					if(isset($data['product_ids'])){
						if(is_array($data['product_ids'])){
							$orderData['product_ids'] = implode(",", $data['product_ids']);
						} else{
							$orderData['product_ids'] = $data['product_ids'];
						}
					}

			        $admin_transaction_id= 0;
			        $affiliate_transaction_id= 0;

					$order_id = 0;
			        if($commissionData['sale_status'] || $commissionData['admin_sale_status']){
						$this->db->insert('integration_orders', $orderData);
			        	$order_id =  $this->db->insert_id();

			        	$this->addLog(array(
							'ip'           => $orderData['ip'],
							'country_code' => $orderData['country_code'],
							'click_id'     => $data['order_id'],
							'domain_name'  => $domain_name,
							'link'         => $domain_name,
							'vendor_id'    => (int)$commissionData['vendor_id'],
							'click_type'   => 'integration_sale',
							'user_id'      => $user_id,
						));
			        }

					if($commissionData['sale_status']){
				        if($commissionData['commission'] > 0){
							$ip_details = array(
								array(
									'ip' => $orderData['ip'],
									'country_code' => $orderData['country_code'],
									'script_name' => $orderData['script_name'],
								)
							);

					        $tran_id = $this->Wallet_model->addTransaction(array(
								'status'         => 0,
								'user_id'        => $user_id,
								'amount'         => $commissionData['commission'],
								'comment'        => 'Commission for '. $data['script_name'] .' | external_order_id '. $o_order_id .' | <br> Sale done from ip_message' ,
								'type'           => 'sale_commission',
								'dis_type'       => $commissionData['ads_type'],
								'comm_from'      => 'ex',
								'reference_id'   => $commissionData['tools_id'],
								'reference_id_2' => $order_id,
								'ip_details'     => json_encode($ip_details),
								'domain_name'    => $domain_name,
								'is_vendor'      => (int)$commissionData['vendor_id'] ? 1 : 0,
								'group_id'=> $tran_group_id,
							));

							if((int)$commissionData['vendor_id']){
								$this->Wallet_model->addTransaction(array(
									'status'         => 0,
									'user_id'        => (int)$commissionData['vendor_id'],
									'from_user_id'   => $user_id,
									'amount'         => -$commissionData['commission'],
									'comment'        => 'Pay Commission for '. $data['script_name'] .' | external_order_id '. $o_order_id .' | <br> Sale done from ip_message' ,
									'type'           => 'sale_commission_vendor_pay',
									'dis_type'       => $commissionData['ads_type'],
									'comm_from'      => 'ex',
									'reference_id'   => $commissionData['tools_id'],
									'reference_id_2' => $order_id,
									'ip_details'     => json_encode($ip_details),
									'domain_name'    => $domain_name,
									'is_vendor'      => (int)$commissionData['vendor_id'] ? 1 : 0,
									'group_id'=> $tran_group_id,
								));
							}

							$affiliate_transaction_id = $tran_id;

							$recursion = $commissionData['recursion'];
							$recursion_endtime = $commissionData['recursion_endtime'];
							$recursion_custom_time = ($recursion == 'custom_time' ) ? $commissionData['recursion_custom_time'] : 0;
			                $this->Wallet_model->addTransactionRecursion(array(
								'transaction_id'          => $tran_id,
								'type'                    => $recursion,
								'custom_time'             => $recursion_custom_time,
								'force_recursion_endtime' => $recursion_endtime,
							));	

							$notificationData = array(
								'notification_url'          => '/integration/orders/',
								'notification_type'         =>  'integration_orders',
								'notification_title'        =>  'New Order Generated in '. $domain_name .'',
								'notification_viewfor'      =>  'admin',
								'notification_actionID'     =>  $order_id,
								'notification_description'  => 'New Order Generated in '. $domain_name .'  On '.date('Y-m-d H:i:s'),
								'notification_is_read'      =>  '0',
								'notification_created_date' =>  date('Y-m-d H:i:s'),
								'notification_ipaddress'    =>  $orderData['ip']
							);
							$this->Product_model->create_data('notification', $notificationData);

							$notificationData = array(
								'notification_url'          => '/integration/orders/',
								'notification_type'         =>  'integration_orders',
								'notification_title'        =>  'New Order Generated in '. $domain_name .'',
								'notification_view_user_id' =>  $user_id,
								'notification_viewfor'      =>  'user',
								'notification_actionID'     =>  $order_id,
								'notification_description'  => 'New Order Generated in '. $domain_name .'  On '.date('Y-m-d H:i:s'),
								'notification_is_read'      =>  '0',
								'notification_created_date' =>  date('Y-m-d H:i:s'),
								'notification_ipaddress'    =>  $orderData['ip']
							);
							$this->Product_model->create_data('notification', $notificationData);

							$level = $this->Product_model->getMyLevel($user_id);

							if($commissionData['main_commission_type'] == 'custom'){
								$referlevelSettings = $commissionData['main_commission']['referlevel'];
								$tmp = $this->Product_model->getSettings('referlevel');
								$referlevelSettings['disabled_for'] = $tmp['disabled_for'];
								$referlevelSettings['status'] = $tmp['status'];
							} else{
			                	$referlevelSettings = $this->Product_model->getSettings('referlevel');
							}

					        $disabled_for = json_decode( (isset($referlevelSettings['disabled_for']) ? $referlevelSettings['disabled_for'] : '[]'),1);

					        $refer_status = true;
					        if((int)$referlevelSettings['status'] == 0){ $refer_status = false; }
					        else if((int)$referlevelSettings['status'] == 2 && in_array($user_id, $disabled_for)){ $refer_status = false; }
		       	 			
		       	 			if($refer_status){
			       	 			$setting = $this->Product_model->getSettings('referlevel');
	        					$max_level = isset($setting['levels']) ? (int)$setting['levels'] : 3;
								//foreach (array(1,2,3) as $l) {
	        					for ($l=1; $l <= $max_level ; $l++) { 
									$s = array();
									if($commissionData['main_commission_type'] == 'custom'){
										$s = $commissionData['main_commission']['referlevel_'. $l];
									} else{
					                	$s = $this->Product_model->getSettings('referlevel_'. $l);
									}

					                $levelUser = (int)$level['level'. $l];
					                if($s && $levelUser > 0){
					                	if($referlevelSettings['sale_type'] == 'percentage'){
					                    	$_giveAmount = (($data['order_total'] * (float)$s['sale_commition']) / 100);
					                	} else{
					                    	$_giveAmount = (float)$s['sale_commition'];
					                	}
					                	if($_giveAmount > 0){
						                    $tran_id = $this->Wallet_model->addTransaction(array(
												'status'       => (int)$setting['autoacceptexternalstore'],
												'user_id'      => $levelUser,
												'amount'       => $_giveAmount,
												'dis_type'     => '',
												'domain_name'  => $domain_name,
												'comm_from'    => 'ex',
												'reference_id_2' => $order_id,
												'ip_details'   => json_encode($ip_details),
												'comment'      => "Level {$l} : ".'Commission for '. $data['script_name'] .' | Order Id '. $o_order_id .' | <br> Sale done from ip_message' ,
												'type'         => 'refer_sale_commission',
												'reference_id' => $o_order_id,
												'is_vendor'      => (int)$commissionData['vendor_id'] ? 1 : 0,
						                    ));

						                    $recursion = $commissionData['recursion'];
						                    $recursion_endtime = $commissionData['recursion_endtime'];
											$recursion_custom_time = ($recursion == 'custom_time' ) ? $commissionData['recursion_custom_time'] : 0;
							                $this->Wallet_model->addTransactionRecursion(array(
												'transaction_id'  => $tran_id,
												'type'            => $recursion,
												'force_recursion_endtime'     => $recursion_endtime,
											));	
					                	}
					                }
					            }
		       	 			}

				            $this->load->model('Mail_model');
				            $this->Mail_model->external_order($order_id, (int)$commissionData['vendor_id']);

				            echo "OK";
				        } else{
				        	echo "C-ZERO";
				        }
					} else{
						echo "S-OFF";
					}

					if($commissionData['admin_sale_status']){
				        if($commissionData['admin_commission'] > 0){
							$ip_details = array(
								array(
									'ip' => $orderData['ip'],
									'country_code' => $orderData['country_code'],
									'script_name' => $orderData['script_name'],
								)
							);

					        $tran_id = $this->Wallet_model->addTransaction(array(
								'status'         => 0,
								'user_id'        => 1,
								'amount'         => $commissionData['admin_commission'],
								'comment'        => 'Admin Commission for '. $data['script_name'] .' | external_order_id '. $o_order_id .' | <br> Sale done from ip_message' ,
								'type'           => 'admin_sale_commission',
								'dis_type'       => $commissionData['ads_type'],
								'comm_from'      => 'ex',
								'reference_id'   => $commissionData['tools_id'],
								'reference_id_2' => $order_id,
								'ip_details'     => json_encode($ip_details),
								'domain_name'    => $domain_name,
								'is_vendor'      => (int)$commissionData['vendor_id'] ? 1 : 0,
								'group_id'=> $tran_group_id,
							));

							$admin_transaction_id = $tran_id;

							if ((int)$commissionData['vendor_id']) {
								$this->Wallet_model->addTransaction(array(
									'status'         => 0,
									'user_id'        => (int)$commissionData['vendor_id'],
									'from_user_id'   => 1,
									'amount'         => -$commissionData['admin_commission'],
									'comment'        => 'Pay Commission for '. $data['script_name'] .' | external_order_id '. $o_order_id .' | <br> Sale done from ip_message' ,
									'type'           => 'admin_sale_commission_v_pay',
									'dis_type'       => $commissionData['ads_type'],
									'comm_from'      => 'ex',
									'reference_id'   => $commissionData['tools_id'],
									'reference_id_2' => $order_id,
									'ip_details'     => json_encode($ip_details),
									'domain_name'    => $domain_name,
									'is_vendor'      => (int)$commissionData['vendor_id'] ? 1 : 0,
									'group_id'=> $tran_group_id,
								));
							}

							$recursion = $commissionData['recursion'];
							$recursion_endtime = $commissionData['recursion_endtime'];
							$recursion_custom_time = ($recursion == 'custom_time' ) ? $commissionData['recursion_custom_time'] : 0;
			                $this->Wallet_model->addTransactionRecursion(array(
								'transaction_id'          => $tran_id,
								'type'                    => $recursion,
								'custom_time'             => $recursion_custom_time,
								'force_recursion_endtime' => $recursion_endtime,
							));	

				            /*$this->load->model('Mail_model');
				            $this->Mail_model->external_order($order_id);*/

				            echo "OK";
				        } else{
				        	echo "C-ZERO";
				        }
					} else{
						echo "S-OFF";
					}

					if($commissionData['sale_status'] || $commissionData['admin_sale_status']){
						$notificationData = array(
							'notification_url'          => '/integration/orders/',
							'notification_type'         =>  'integration_orders',
							'notification_title'        =>  'New Order Generated in Your Market tool '. $domain_name .'',
							'notification_view_user_id' =>  $commissionData['vendor_id'],
							'notification_viewfor'      =>  'user',
							'notification_actionID'     =>  $order_id,
							'notification_description'  => 'New Order Generated in '. $domain_name .'  On '.date('Y-m-d H:i:s'),
							'notification_is_read'      =>  '0',
							'notification_created_date' =>  date('Y-m-d H:i:s'),
							'notification_ipaddress'    =>  $orderData['ip']
						);
						$this->Product_model->create_data('notification', $notificationData);
					}

					$this->db->where('id', $order_id);
					$this->db->update('integration_orders',[
						'admin_tran' => $admin_transaction_id,
						'affiliate_tran' => $affiliate_transaction_id,
					]);

				} else{
					echo "AA";
				}
			} else{
				echo "UNF";
			}
		} else{
			echo "AINF";
		}
	}

	public function addClick($data){
		if(!isset($data['ip'])){ $data['ip'] = $_SERVER['REMOTE_ADDR']; }
		if(isset($data['af_id']) && $data['af_id'] && $data['ip']){

			list($user_id,$ads_id) = explode("-", _encrypt_decrypt(parse_affiliate_id($data['af_id']),'decrypt'));

			$user = $this->db->query("SELECT id FROM users WHERE type='user' AND id = ". (int)$user_id)->row();

			$click_type = '';
			$is_action = 0;
			$domain_name = url_to_domain(base64_decode($data['base_url']));
			
			$action_code = isset($data['actionCode']) ? trim($data['actionCode']) : '';
			$page_name = isset($data['page_name']) ? trim($data['page_name']) : '';

			if($action_code){
				$setting = $this->getTollByAction($action_code);
				$is_action = 1;
				$click_type = 'action';
				if($setting['tool_type'] != 'action') die('na');

			} else if($page_name != ''){
				$setting = $this->getAdsByID($ads_id);
				$click_type = 'general_click';
				if($setting['general_code'] != $page_name || $setting['tool_type'] != 'general_click') die('ng');

			} else {
				$action_code = '_af_product_click';
				$setting = $this->getAdsByID($ads_id);
				$click_type = 'product_click';

				$setting['vendor_id'] = $setting['integration_programs_vendor_id'];
				
				//if($setting['tool_type'] != 'program' && !$setting['click_status']) die('np');
				if(!$setting['click_status'] && !$setting['admin_click_status']) die('np');
			}

			$tran_group_id = time();
			 
			if($user && $setting){
				$checkAlreadyClick = $this->db->query("SELECT id FROM integration_clicks_action WHERE 
					base_url    = ". $this->db->escape($domain_name) ." AND 
					user_id     = ". $this->db->escape($user_id) ." AND
					ads_id      = ". $this->db->escape($ads_id) ." AND
					page_name   = ". $this->db->escape($page_name) ." AND
					product_id  = ". $this->db->escape((int)$data['product_id']) ." AND
					action_code = ". $this->db->escape($action_code) ." AND
					ip          = ". $this->db->escape($data['ip']) ."
				")->row();

				$total_vendor_need_to_pay = 0;

				$_ip = $this->Product_model->ip_info($data['ip']);

				if(!$checkAlreadyClick){
					if($click_type == 'product_click' && !$setting['click_status'] ) die("np");

					$clickData = array(
						'product_id'  => (int)$data['product_id'],
						'script_name' => $data['script_name'],
						'action_code' => $action_code,
						'page_name'   => $page_name,
						'user_id'     => $user_id,
						'commission'  => 0,
						'ads_id'      => $ads_id,
						'is_action'   => $is_action,
						'tools_id'    => $setting['tools_id'],
					);

					if(isset($data['base_url'])){
						$clickData['base_url'] = $domain_name;
					} else{
						$clickData['base_url'] = '';
					}

					//$_ip = $this->Product_model->ip_info($data['ip']);
					$clickData['ip'] = $data['ip'];
					$clickData['country_code'] = @$_ip['country_code'];
				
					$this->db->insert('integration_clicks_action', $clickData);
			    	$click_id =  $this->db->insert_id();
			    	$this->callPostback($click_id, $setting['marketpostback'], $_ip, $click_type);

					$this->addLog(array(
						'ip'           => $clickData['ip'],
						'country_code' => $clickData['country_code'],
						'click_id'     => $click_id,
						'domain_name'  => $domain_name,
						'link'         => isset($data['current_page_url']) ? url_to_clean(base64_decode($data['current_page_url'])) : '',
						'click_type'   => $click_type,
						'user_id'      => $user_id,
						'vendor_id'    => (int)$setting['vendor_id'],
					));

					$countTotalClicks = $this->db->query("SELECT id,page_name,ip,country_code,base_url,product_id,script_name FROM integration_clicks_action WHERE
						commission  = 0 AND
						script_name = ". $this->db->escape($data['script_name']) ." AND 
						base_url    = ". $this->db->escape($domain_name) ." AND 
						user_id     = ". $this->db->escape($user_id) ." AND
						action_code = ". $this->db->escape($action_code) ." AND
						page_name   = ". $this->db->escape($page_name) ." AND
						ads_id      = ". $this->db->escape($ads_id) ." AND
						product_id  = ". $this->db->escape((int)$data['product_id']) ."
					");
		        	$tC = $countTotalClicks->num_rows();

		        	
		        	$reference_id_2 = '';
		        	if($page_name != '' && $setting['tool_type'] == 'general_click'){
						$needClick  = $setting['general_click'];
						$giveAmount = $setting['general_amount'];

						$reference_id_2 = '__general_click__';
		        	} 
		        	else if($action_code == '_af_product_click'){
						$needClick  = $setting['commission_number_of_click'];
						$giveAmount = $setting['commission_click_commission'];

		        	} else {
						$needClick  = $setting['action_click'];
						$giveAmount = $setting['action_amount'];
						$reference_id_2 = $action_code;
		        	}
		        	
	            	if($needClick <= $tC){
		                $ips = [];
		                $website_link = '';
		                $product_id = 0;
		                foreach ($countTotalClicks->result() as $vv) {
		                    $ips[] = array(
								'id'           => $vv->id,
								'ip'           => $vv->ip,
								'country_code' => $vv->country_code,
								'script_name'  => $vv->script_name,
								'page_name'    => $vv->page_name,
		                    );

		                    $website_link = $vv->base_url;
		                    $product_id = $vv->product_id;
		                }

		                $noti_msg = '';
		                $noti_title = '';
		                if($page_name != '' && $setting['tool_type'] == 'general_click'){
							$message_vendor = "Pay Commission for {$tC} General Click On {$website_link} | Name : {$page_name}  <br> Clicked done from ip_message";
							$message = "Commission for {$tC} General Click On {$website_link} | Name : {$page_name}  <br> Clicked done from ip_message";
							$noti_msg = "Commission for {$tC} General Click On {$website_link} | Name : {$page_name}";
		                	$noti_title = 'New Click Added in '. $domain_name;
			        	} else if($action_code == '_af_product_click'){
							$message_vendor = "Pay Commission for {$tC} click On {$website_link} |  Product ID: {$product_id}  <br> Clicked done from ip_message";
							$message = "Commission for {$tC} click On {$website_link} |  Product ID: {$product_id}  <br> Clicked done from ip_message";
							$noti_msg = "Commission for {$tC} Click On {$website_link} | Product ID: {$product_id}";
		                	$noti_title = 'New Product Click Added in '. $domain_name;
			        	} else {
							$message_vendor = "Pay Commission for {$tC} Action On {$website_link} |  Action Code : {$action_code}  <br> Clicked done from ip_message";
							$message = "Commission for {$tC} Action On {$website_link} |  Action Code : {$action_code}  <br> Clicked done from ip_message";
							$noti_msg = "Commission for {$tC} Action On {$website_link} | Action Code : {$action_code}";
		                	$noti_title = 'New Action Added in '. $domain_name;
			        	}

		                $this->load->model('Mail_model');
		                $referlevelSettings = $this->Product_model->getSettings('referlevel');
		                $tran_id = $this->Wallet_model->addTransaction(array(
							'status'         => $is_action ? (int)$referlevelSettings['default_action_status'] : 1,
							'user_id'        => $user_id,
							'amount'         => $giveAmount,
							'dis_type'       => $setting['ads_type'],
							'comm_from'      => 'ex',
							'comment'        => $message,
							'type'           => 'external_click_commission',
							'reference_id'   => $setting['tools_id'],
							'reference_id_2' => $reference_id_2,
							'page_name'      => $page_name,
							'ip_details'     => json_encode($ips),
							'domain_name'    => $domain_name,
							'is_action'      => $is_action,
							'group_id'      => $tran_group_id,
							'is_vendor'      => $setting['vendor_id'] ? 1 : 0,
		                ));
		                // file_put_contents('./setting.txt', json_encode($setting) .PHP_EOL , FILE_APPEND | LOCK_EX); 
		                if($setting['vendor_id']){
		                	$this->Wallet_model->addTransaction(array(
								'status'         => $is_action ? (int)$referlevelSettings['default_action_status'] : 1,
								'user_id'        => $setting['vendor_id'],
								'amount'         => -$giveAmount,
								'from_user_id'   => $user_id,
								'dis_type'       => $setting['ads_type'],
								'comm_from'      => 'ex',
								'comment'        => $message_vendor,
								'type'           => 'external_click_comm_pay',
								'reference_id'   => $setting['tools_id'],
								'reference_id_2' => $reference_id_2,
								'page_name'      => $page_name,
								'ip_details'     => json_encode($ips),
								'domain_name'    => $domain_name,
								'is_action'      => $is_action,
								'group_id'       => $tran_group_id,
								'is_vendor'      => $setting['vendor_id'] ? 1 : 0,
			                ));
		                }

						$recursion = $setting['recursion'];
						$recursion_endtime = $commissionData['recursion_endtime'];
						$recursion_custom_time = ($recursion == 'custom_time' ) ? $setting['recursion_custom_time'] : 0;
		                $this->Wallet_model->addTransactionRecursion(array(
							'transaction_id'  => $tran_id,
							'type'            => $recursion,
							'custom_time'     => $recursion_custom_time,
							'force_recursion_endtime'     => $recursion_endtime,
						));	

		                $notificationData = array(
							'notification_url'          => '/mywallet/',
							'notification_type'         =>  'integration_click',
							'notification_viewfor'      =>  'admin',
							'notification_is_read'      =>  '0',
							'notification_title'        =>  $noti_title,
							'notification_actionID'     =>  $tran_id,
							'notification_description'  =>  $noti_msg .'  On '.date('Y-m-d H:i:s'),
							'notification_ipaddress'    =>  $data['ip'],
							'notification_created_date' =>  date('Y-m-d H:i:s'),
						);
						$this->Product_model->create_data('notification', $notificationData);

						$notificationData = array(
							'notification_url'          => '/mywallet/',
							'notification_type'         =>  'integration_click',
							'notification_viewfor'      =>  'user',
							'notification_is_read'      =>  '0',
							'notification_title'        =>  $noti_title,
							'notification_view_user_id' =>  $user_id,
							'notification_actionID'     =>  $tran_id,
							'notification_description'  => 	$noti_msg .'  On '.date('Y-m-d H:i:s'),
							'notification_ipaddress'    =>  $data['ip'],
							'notification_created_date' =>  date('Y-m-d H:i:s'),
						);
						$this->Product_model->create_data('notification', $notificationData);

		                $this->db->query("
		                	UPDATE  integration_clicks_action 
		                	SET 
		                		commission = 1 
	                		WHERE 
								base_url    = ". $this->db->escape($domain_name) ." AND 
								user_id     = ". $this->db->escape($user_id) ." AND
								ads_id      = ". $this->db->escape($ads_id) ." AND
								page_name   = ". $this->db->escape($page_name) ." AND
								action_code = ". $this->db->escape($action_code) ." AND
								product_id  = ". $this->db->escape((int)$data['product_id']) ."
						");
			        }
				} else{
					echo "ak";
				}

				if($setting['vendor_id']){
					if($click_type == 'product_click' && !$setting['admin_click_status'] ) die("np");

					$checkAlreadyClick = $this->db->query("SELECT id FROM integration_admin_clicks_action WHERE 
						base_url    = ". $this->db->escape($domain_name) ." AND 
						user_id     = ". $this->db->escape($user_id) ." AND
						ads_id      = ". $this->db->escape($ads_id) ." AND
						page_name   = ". $this->db->escape($page_name) ." AND
						product_id  = ". $this->db->escape((int)$data['product_id']) ." AND
						action_code = ". $this->db->escape($action_code) ." AND
						ip          = ". $this->db->escape($data['ip']) ."
					")->row();

					if(!$checkAlreadyClick){
						$clickData = array(
							'product_id'  => (int)$data['product_id'],
							'script_name' => $data['script_name'],
							'action_code' => $action_code,
							'page_name'   => $page_name,
							'user_id'     => $user_id,
							'commission'  => 0,
							'ads_id'      => $ads_id,
							'is_action'   => $is_action,
							'tools_id'    => $setting['tools_id'],
						);

						if(isset($data['base_url'])){
							$clickData['base_url'] = $domain_name;
						} else{
							$clickData['base_url'] = '';
						}

						$clickData['ip'] = $data['ip'];
						$clickData['country_code'] = @$_ip['country_code'];
					
						$this->db->insert('integration_admin_clicks_action', $clickData);
				    	$click_id =  $this->db->insert_id();

						$countTotalClicks = $this->db->query("SELECT id,page_name,ip,country_code,base_url,product_id,script_name FROM integration_admin_clicks_action WHERE
							commission  = 0 AND
							script_name = ". $this->db->escape($data['script_name']) ." AND 
							base_url    = ". $this->db->escape($domain_name) ." AND 
							user_id     = ". $this->db->escape($user_id) ." AND
							action_code = ". $this->db->escape($action_code) ." AND
							page_name   = ". $this->db->escape($page_name) ." AND
							ads_id      = ". $this->db->escape($ads_id) ." AND
							product_id  = ". $this->db->escape((int)$data['product_id']) ."
						");
			        	$tC = $countTotalClicks->num_rows();
			        	
			        	$reference_id_2 = '';
			        	if($page_name != '' && $setting['tool_type'] == 'general_click'){
							$needClick  = $setting['admin_general_click'];
							$giveAmount = $setting['admin_general_amount'];

							$reference_id_2 = '__general_click__';
			        	} 
			        	else if($action_code == '_af_product_click'){
							$needClick  = $setting['admin_commission_number_of_click'];
							$giveAmount = $setting['admin_commission_click_commission'];

			        	} else {
							$needClick  = $setting['admin_action_click'];
							$giveAmount = $setting['admin_action_amount'];
							$reference_id_2 = $action_code;
			        	}
			        	
		            	if($needClick <= $tC){
			                $ips = [];
			                $website_link = '';
			                $product_id = 0;
			                foreach ($countTotalClicks->result() as $vv) {
			                    $ips[] = array(
									'id'           => $vv->id,
									'ip'           => $vv->ip,
									'country_code' => $vv->country_code,
									'script_name'  => $vv->script_name,
									'page_name'    => $vv->page_name,
			                    );

			                    $website_link = $vv->base_url;
			                    $product_id = $vv->product_id;
			                }

			                $noti_msg = '';
			                $noti_title = '';
			                if($page_name != '' && $setting['tool_type'] == 'general_click'){
								$message_vendor = "Commission Pay for {$tC} General Click On {$website_link} | Name : {$page_name}  <br> Clicked done from ip_message";
								$message = "Admin Commission for {$tC} General Click On {$website_link} | Name : {$page_name}  <br> Clicked done from ip_message";
								$noti_msg = "Admin Commission for {$tC} General Click On {$website_link} | Name : {$page_name}";
			                	$noti_title = 'Admin New Click Added in '. $domain_name;
				        	} else if($action_code == '_af_product_click'){
								$message_vendor = "Commission Pay for {$tC} click On {$website_link} |  Product ID: {$product_id}  <br> Clicked done from ip_message";
								$message = "Admin Commission for {$tC} click On {$website_link} |  Product ID: {$product_id}  <br> Clicked done from ip_message";
								$noti_msg = "Admin Commission for {$tC} Click On {$website_link} | Product ID: {$product_id}";
			                	$noti_title = 'Admin New Product Click Added in '. $domain_name;
				        	} else {
								$message_vendor = "Commission Pay for {$tC} Action On {$website_link} |  Action Code : {$action_code}  <br> Clicked done from ip_message";
								$message = "Admin Commission for {$tC} Action On {$website_link} |  Action Code : {$action_code}  <br> Clicked done from ip_message";
								$noti_msg = "Admin Commission for {$tC} Action On {$website_link} | Action Code : {$action_code}";
			                	$noti_title = 'Admin New Action Added in '. $domain_name;
				        	}

			                $this->load->model('Mail_model');
			                $referlevelSettings = $this->Product_model->getSettings('referlevel');
			                $tran_id = $this->Wallet_model->addTransaction(array(
								'status'         => $is_action ? (int)$referlevelSettings['default_action_status'] : 1,
								'user_id'        => 1,
								'amount'         => $giveAmount,
								'dis_type'       => $setting['ads_type'],
								'comm_from'      => 'ex',
								'comment'        => $message,
								'type'           => 'external_click_comm_admin',
								'reference_id'   => $setting['tools_id'],
								'reference_id_2' => $reference_id_2,
								'page_name'      => $page_name,
								'ip_details'     => json_encode($ips),
								'domain_name'    => $domain_name,
								'is_action'      => $is_action,
								'group_id'      => $tran_group_id,
								'is_vendor'      => $setting['vendor_id'] ? 1 : 0,
			                ));

			               
		                	$this->Wallet_model->addTransaction(array(
								'status'         => $is_action ? (int)$referlevelSettings['default_action_status'] : 1,
								'user_id'        => $setting['vendor_id'],
								'from_user_id'   => 1,
								'amount'         => -$giveAmount,
								'dis_type'       => $setting['ads_type'],
								'comm_from'      => 'ex',
								'comment'        => $message_vendor,
								'type'           => 'external_click_comm_pay',
								'reference_id'   => $setting['tools_id'],
								'reference_id_2' => $reference_id_2,
								'page_name'      => $page_name,
								'ip_details'     => json_encode($ips),
								'domain_name'    => $domain_name,
								'is_action'      => $is_action,
								'group_id'      => $tran_group_id,
								'is_vendor'      => $setting['vendor_id'] ? 1 : 0,
			                ));
			                

							$recursion = $setting['recursion'];
							$recursion_endtime = $commissionData['recursion_endtime'];
							$recursion_custom_time = ($recursion == 'custom_time' ) ? $setting['recursion_custom_time'] : 0;
			                $this->Wallet_model->addTransactionRecursion(array(
								'transaction_id'          => $tran_id,
								'type'                    => $recursion,
								'custom_time'             => $recursion_custom_time,
								'force_recursion_endtime' => $recursion_endtime,
							));	

			                $notificationData = array(
								'notification_url'          => '/mywallet/',
								'notification_type'         =>  'integration_click',
								'notification_viewfor'      =>  'admin',
								'notification_is_read'      =>  '0',
								'notification_title'        =>  $noti_title,
								'notification_actionID'     =>  $tran_id,
								'notification_description'  =>  $noti_msg .'  On '.date('Y-m-d H:i:s'),
								'notification_ipaddress'    =>  $data['ip'],
								'notification_created_date' =>  date('Y-m-d H:i:s'),
							);
							$this->Product_model->create_data('notification', $notificationData);

			                $this->db->query("
			                	UPDATE  integration_admin_clicks_action 
			                	SET 
			                		commission = 1 
		                		WHERE 
									base_url    = ". $this->db->escape($domain_name) ." AND 
									user_id     = ". $this->db->escape($user_id) ." AND
									ads_id      = ". $this->db->escape($ads_id) ." AND
									page_name   = ". $this->db->escape($page_name) ." AND
									action_code = ". $this->db->escape($action_code) ." AND
									product_id  = ". $this->db->escape((int)$data['product_id']) ."
							");
				        }
					} else{
						echo "admin ak";
					}
				}
				
				$data['action_code']           = $action_code;
				$data['page_name']             = $page_name;
				$data['ads_id']                = $ads_id;
				$data['is_action']             = $is_action;
				$data['tools_id']              = $setting['tools_id'];
				$data['tool_type']             = $setting['tool_type'];
				$data['main_commission_type']  = $setting['main_commission_type'];
				$data['main_commission']       = $setting['main_commission'];
				$data['recursion']             = $setting['recursion'];
				$data['recursion_custom_time'] = $setting['recursion_custom_time'];
				$data['recursion_endtime']     = $setting['recursion_endtime'];

				$this->referClick((int)$data['product_id'], $user_id,$domain_name, $_ip, $data);
			} else{
				echo "bl";
			}
		}
		die("OK");
	}

	public function callPostback($id, $settings, $ipInfo, $type){
		$sendPostBack = false;
		if($settings['status'] == 'custom' || $settings['status'] == 'default'){
			$url = $settings['url'];

			$data = [
				'city'           => $ipInfo['city'],
				'regionCode'     => $ipInfo['regionCode'],
				'regionName'     => $ipInfo['regionName'],
				'countryCode'    => $ipInfo['countryCode'],
				'countryName'    => $ipInfo['countryName'],
				'continentName'  => $ipInfo['continentName'],
				'timezone'       => $ipInfo['timezone'],
				'currencyCode'   => $ipInfo['currencyCode'],
				'currencySymbol' => $ipInfo['currencySymbol'],
				'ip'             => $ipInfo['ip'],
				'type'           => $type,
				'id'             => (int)$id,
			];

			$postData = [];
			if($settings['status'] == 'custom'){
				$allowOnly = (isset($settings['dynamicparam']) && is_array($settings['dynamicparam'])) ? $settings['dynamicparam'] : [];
				foreach ($data as $key => $value) {
					if(in_array($key, $allowOnly)){ $postData[$key] = $data[$key]; }
				}

				$static = (isset($settings['static']) && is_array($settings['static'])) ? $settings['static'] : [];
				foreach ($static as $key => $value) {
					if(isset($value['key']) && $value['key'] != ''){ 
						$postData[$value['key']] = $value['value']; 
					}
				}

				$sendPostBack = true;
			} else {
				$default_marketpostback = $this->Product_model->getSettings('marketpostback');
				if((int)$default_marketpostback['status'] == 1){
					$url = $default_marketpostback['url'];
					$allowOnly = (isset($default_marketpostback['dynamicparam'])) ? json_decode($default_marketpostback['dynamicparam'],1) : [];
					foreach ($data as $key => $value) {
						if(in_array($key, $allowOnly)){ $postData[$key] = $data[$key]; }
					}

					$static = isset($default_marketpostback['static']) ? json_decode($default_marketpostback['static'],1) : [];
					foreach ($static as $key => $value) {
						if(isset($value['key']) && $value['key'] != ''){ 
							$postData[$value['key']] = $value['value']; 
						}
					}

					$sendPostBack = true;
				}

			}
		}

		if($sendPostBack && $url){
			$curl = curl_init($url);
			$request = http_build_query($postData);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_exec($curl);

			return false;
		}
	}

	public function addLog($data){
		$this->load->library('Uagent');
		$this->uagent->init();

		$logData = array(
			'agent'          => $this->uagent->string,
			'browserName'    => $this->uagent->browserName,
			'browserVersion' => $this->uagent->browserVersion,
			'systemString'   => $this->uagent->systemString,
			'osPlatform'     => $this->uagent->osPlatform,
			'osVersion'      => $this->uagent->osVersion,
			'osShortVersion' => $this->uagent->osShortVersion,
			'mobileName'     => $this->uagent->mobileName,
			'osArch'         => $this->uagent->osArch,
			'isIntel'        => (int)$this->uagent->isIntel,
			'isMobile'       => (int)$this->uagent->isMobile,
			'isAMD'          => (int)$this->uagent->isAMD,
			'isPPC'          => (int)$this->uagent->isPPC,
			'ip'             => $data['ip'],
			'country_code'   => $data['country_code'],
			'click_id'       => $data['click_id'],
			'base_url'       => $data['domain_name'],
			'link'           => $data['link'],
			'click_type'     => $data['click_type'],
			'user_id'        => $data['user_id'],
			'vendor_id'        => isset($data['vendor_id']) ? (int)$data['vendor_id'] : 0,
			'created_at'     => date("Y-m-d H:i:s"),
		);

		$this->db->insert('integration_clicks_logs', $logData);
	}

	public function referClick($product_id, $user_id,$base_url, $ip_details, $data) {

		if($data['main_commission_type'] == 'custom'){
			$store_commition_setting = $data['main_commission']['referlevel'];
			$tmp = $this->Product_model->getSettings('referlevel');
			$store_commition_setting['disabled_for'] = $tmp['disabled_for'];
			$store_commition_setting['status'] = $tmp['status'];
		} else{
			$store_commition_setting = $this->Product_model->getSettings('referlevel');
		}

        $disabled_for = json_decode( (isset($store_commition_setting['disabled_for']) ? $store_commition_setting['disabled_for'] : '[]'),1); 
        if((int)$store_commition_setting['status'] == 0){ return false; }
        else if((int)$store_commition_setting['status'] == 2 && in_array($user_id, $disabled_for)){ return false; }

		$script_name  = $data['script_name'];
		$ip_address   = $ip_details['ip'];
		$country_code = $ip_details['country_code'];
		$action_code  = $data['action_code'];
		$page_name    = $data['page_name'];
		$ads_id       = $data['ads_id'];
		$is_action    = $data['is_action'];
		$tools_id     = $data['tools_id'];
		$tool_type    = $data['tool_type'];

        $level = $this->Product_model->getMyLevel($user_id);

        $count_for = '';
        $setting = $this->Product_model->getSettings('referlevel');
		$max_level = isset($setting['levels']) ? (int)$setting['levels'] : 3;
		for ($l=1; $l <= $max_level ; $l++) { 
        	$count_for .= (int)$level['level'. $l] > 0 ? $level['level'. $l] ."," : "";
		}
        /*$count_for .= (int)$level['level1'] > 0 ? $level['level1'] ."," : "";
        $count_for .= (int)$level['level2'] > 0 ? $level['level2'] ."," : "";
        $count_for .= (int)$level['level3'] > 0 ? $level['level3'] ."," : "";*/
        
        if ($count_for) {
            $this->db->from('integration_refer_product_action');
			$this->db->where('product_id', $product_id);
			$this->db->where('user_id', $user_id);
			$this->db->where('user_ip', $ip_address);
			$this->db->where('script_name', $script_name);
			$this->db->where('base_url', $base_url);
			$this->db->where('action_code', $action_code);
			$this->db->where('page_name', $page_name);
			$this->db->where('ads_id', $ads_id);
			$this->db->where('is_action', $is_action);
			$this->db->where('tools_id', $tools_id);
            $result = $this->db->get()->num_rows();

            if($result == 0){
                $new_record = array(
					'product_id'    => $product_id,
					'base_url'      => $base_url,
					'count_for'     => trim($count_for,","),
					'user_id'       => $user_id,
					'user_ip'       => $ip_address,
					'country_code'  => $country_code,
					'script_name'   => $script_name,
					'created_at'    => date('Y-m-d h:i:s'),
					'counter'       => 1,
					'action_code'   => $action_code,
					'page_name'     => $page_name,
					'ads_id'        => $ads_id,
					'is_action'     => $is_action,
					'tools_id'      => $tools_id,
					'pay_commition' => 0,
                );
               
                $this->db->insert('integration_refer_product_action', $new_record);
            }
        }

        /* Give Ferer Commition */
        $totalClick = $this->db->query("
        	SELECT id,page_name,user_ip,country_code,base_url,product_id,script_name
        	FROM  integration_refer_product_action 
        	WHERE 
				pay_commition = 0 AND 
				base_url    = '{$base_url}' AND  
				user_id     = '{$user_id}' AND  
				script_name = '{$script_name}' AND  
				product_id  = '{$product_id}' AND
				action_code = '{$action_code}' AND
				page_name   = '{$page_name}' AND
				ads_id      = '{$ads_id}'
		");

        //$store_commition_setting = $this->Product_model->getSettings('referlevel');
        $subkey = '';
        $tC = $totalClick->num_rows();
       	$setting = $this->Product_model->getSettings('referlevel');


       	$status = 1;
        $reference_id_2 = '';
        if($page_name != '' && $tool_type == 'general_click'){
        	$_needClick = (int)$store_commition_setting['click'];
			$message = "Referred Commission for {$tC} General Click On {$base_url} | Name : {$page_name}  <br> Clicked done from ip_message";
			$subkey = 'ex_commition';
			$reference_id_2 = '__general_click__';
    	} else if($action_code == '_af_product_click'){
    		$_needClick = (int)$store_commition_setting['ex_action_click'];
			$message = "Referred Commission for {$tC} Click On {$base_url} |  Product ID: {$product_id}  <br> Clicked done from ip_message";
			$subkey = 'ex_commition';
    	} else {
    		$_needClick = (int)$store_commition_setting['ex_action_click'];
			$message = "Referred Commission for {$tC} Action On {$base_url} |  Action Code : {$action_code}  <br> Clicked done from ip_message";
			$subkey = 'ex_action_commition';
			$reference_id_2 = $action_code;

			$status = (int)$setting['autoacceptaction'];
			$status = (int)$setting['default_action_status'] ? $status : 0;
    	}

        if($tC >= $_needClick){
            $this->load->model('Mail_model');

            foreach ($totalClick->result() as $vv) {
                $ips[] = array(
					'id'           => $vv->id,
					'ip'           => $vv->user_ip,
					'country_code' => $vv->country_code,
					'script_name'  => $vv->script_name,
					'page_name'    => $vv->page_name,
                );
            }

            
			$max_level = isset($setting['levels']) ? (int)$setting['levels'] : 3;
            //foreach (array(1,2,3) as $l) {
			for ($l=1; $l <= $max_level ; $l++) { 
            	$s = array();

            	if($data['main_commission_type'] == 'custom'){
					$s = $data['main_commission']['referlevel_'. $l];
				} else{
                	$s = $this->Product_model->getSettings('referlevel_'. $l);
				}

                $levelUser = (int)$level['level'. $l];
                if($s && $levelUser > 0){
		        	$_giveAmount = (float)$s[$subkey];
		        	if($_giveAmount > 0){
	                    $tran_id = $this->Wallet_model->addTransaction(array(
							'status'         => $status,
							'user_id'        => $levelUser,
							'amount'         => $_giveAmount,
							'domain_name'    => $base_url,
							'comm_from'      => 'ex',
							'dis_type'       => '',
							'ip_details'     => json_encode($ips),
							'comment'        => "Level {$l} {$message}",
							'type'           => 'refer_click_commission',
							'reference_id_2' => $reference_id_2,
							'page_name'      => $page_name,
							'is_action'      => $is_action,
							'reference_id'   => $product_id,
	                    ));

	                    $recursion = $data['recursion'];
	                    $recursion_endtime = $data['recursion_endtime'];
						$recursion_custom_time = ($recursion == 'custom_time' ) ? $data['recursion_custom_time'] : 0;
		                $this->Wallet_model->addTransactionRecursion(array(
							'transaction_id'  => $tran_id,
							'type'            => $recursion,
							'custom_time'     => $recursion_custom_time,
							'force_recursion_endtime'     => $recursion_endtime,
						));	
		        	}

                }
            }
            $this->db->query("UPDATE integration_refer_product_action SET pay_commition = 1 WHERE 
				pay_commition = 0 AND 
				base_url    = '{$base_url}' AND  
				user_id     = '{$user_id}' AND  
				script_name = '{$script_name}' AND  
				product_id  = '{$product_id}' AND
				action_code = '{$action_code}' AND
				page_name   = '{$page_name}' AND
				ads_id      = '{$ads_id}'
        	");
        }
    }

    public function getTollByAction($action_code){
    	$data = $this->db->select('
    		integration_tools.id as tools_id,
    		integration_tools.vendor_id,
    		integration_tools.type as ads_type,
    		integration_tools.status as click_status,
    		integration_tools.action_click,
    		integration_tools.action_amount,
    		integration_tools.admin_action_click,
    		integration_tools.admin_action_amount,
    		integration_tools.action_code,
    		integration_tools.tool_type,
    		integration_tools.recursion,
    		integration_tools.recursion_endtime,
    		integration_tools.marketpostback,
			integration_tools.recursion_custom_time,
    		integration_tools.commission_type as main_commission_type,
			integration_tools.commission as main_commission
		')
		->from('integration_tools')
		->where("action_code", 	$action_code)
		->get()
		->row_array();

		if($data){
			$data['main_commission'] = json_decode($data['main_commission'],1);
			$data['marketpostback'] = json_decode($data['marketpostback'],1);
		}

		return $data;
    }

	public function getAdsByID($ads_id){
		$data = $this->db
			->select("
				integration_tools_ads.*,
				integration_programs.id as program_id,
				integration_programs.commission_type,
				integration_programs.commission_sale,
				integration_programs.sale_status,
				integration_programs.commission_number_of_click,
				integration_programs.commission_click_commission,
				integration_programs.click_status,
				integration_programs.admin_commission_type,
				integration_programs.admin_commission_sale,
				integration_programs.admin_commission_number_of_click,
				integration_programs.admin_commission_click_commission,
				integration_programs.admin_click_status,
				integration_programs.admin_sale_status,
				integration_programs.vendor_id as integration_programs_vendor_id,


				integration_tools.tool_type,
				integration_tools.marketpostback,
				integration_tools.general_click,
				integration_tools.general_amount,

				integration_tools.admin_general_click,
				integration_tools.admin_general_amount,
				integration_tools.admin_action_click,
				integration_tools.admin_action_amount,
				integration_tools.vendor_id,

				integration_tools.general_code,
				integration_tools.recursion,
				integration_tools.recursion_endtime,
				integration_tools.recursion_custom_time,
				integration_tools.commission_type as main_commission_type,
				integration_tools.commission as main_commission
			")
			->from("integration_tools_ads")
			->join('integration_tools','integration_tools.id=integration_tools_ads.tools_id','left')
			->join('integration_programs','integration_programs.id=integration_tools.program_id','left')
			->where("integration_tools_ads.id",(int)$ads_id)
			->get()
			->row_array();

		if($data){
			$data['main_commission'] = json_decode($data['main_commission'],1);
			$data['marketpostback'] = json_decode($data['marketpostback'],1);
		}

		return $data;
	}

    private function calcCommitions($data, $user_id,$ads_id){
    	$commissionSetting = $this->getAdsByID($ads_id);
    	 
    	if($commissionSetting){
	        $product_price = (float)$data['order_total'];
	        $commission = 0;
	        $this->load->model('Product_model');
	        
	        $commissionType = strtolower($commissionSetting['commission_type']);
	        if($commissionType == 'percentage'){
	            $commissionType = 'percentage ('. $commissionSetting['commission_sale'] .'%)';
	            $commission = max(($product_price * $commissionSetting['commission_sale']),1) / 100;
	        }
	        else if($commissionType == 'fixed'){
	            $commission = $commissionSetting['commission_sale'];
	        }

	        $admin_commission_type = $admin_commission = '';
	        if($commissionSetting['integration_programs_vendor_id']){
		        $admin_commission_type = strtolower($commissionSetting['admin_commission_type']);
		        if($admin_commission_type == 'percentage'){
		            $admin_commission_type = 'percentage ('. $commissionSetting['admin_commission_sale'] .'%)';
		            $admin_commission = max(($product_price * $commissionSetting['admin_commission_sale']),1) / 100;
		        }
		        else if($admin_commission_type == 'fixed'){
		            $admin_commission = $commissionSetting['admin_commission_sale'];
		        }
	        }
	       	
	        return array(
				'commission_type'       => $commissionType,
				'commission'            => (float)$commission,
				'tools_id'              => $commissionSetting['tools_id'],
				'sale_status'           => $commissionSetting['sale_status'],
				'main_commission'       => $commissionSetting['main_commission'],
				'main_commission_type'  => $commissionSetting['main_commission_type'],
				'recursion'             => $commissionSetting['recursion'],
				'recursion_custom_time' => $commissionSetting['recursion_custom_time'],

				'admin_sale_status'     => $commissionSetting['admin_sale_status'],
				'admin_commission_type' => $commissionSetting['admin_commission_type'],
				'admin_commission_sale' => $commissionSetting['admin_commission_sale'],
				'vendor_id' => $commissionSetting['integration_programs_vendor_id'],
				'admin_commission'      => $admin_commission,
	        );
    	}
    }

    public function getProgramByID($program_id){
    	return $this->db->select("integration_programs.*,users.username,users.email, CONCAT(users.firstname,' ',users.lastname) as vendor_name")
    	->from("integration_programs")
    	->join("users","users.id=integration_programs.vendor_id","left")
    	->where("integration_programs.id",(int)$program_id)->get()->row_array();
    }
    public function getPrograms($filter = array()){
    	$query =  $this->db->select("integration_programs.*,users.username,(SELECT count(id)  FROM integration_tools WHERE integration_tools.program_id = integration_programs.id ) as associate_programns ")->from("integration_programs");

    	$query->join("users","users.id=integration_programs.vendor_id","left");

    	if (isset($filter['vendor_id'])) {
    		if($filter['vendor_id'] == 0){
    			$this->db->where('(integration_programs.vendor_id IS NULL OR integration_programs.vendor_id='. (int)$filter['vendor_id'].")");
    		} else {
    			$this->db->where('integration_programs.vendor_id='. (int)$filter['vendor_id']);
    		}
    	}
    	if (isset($filter['status'])) {
    		$this->db->where('integration_programs.status='. (int)$filter['status']);
    	}

    	$query->order_by("integration_programs.id","DESC");
    	$programs = $query->get()->result_array();

    	return $programs;
    }
    public function editProgram($data, $program_id = 0,$editBy='admin', $vendor_id= 0){
    	$program = array(
			'name' => $data['name'],
		);

		$old = $this->db->query("SELECT * FROM integration_programs WHERE id=". (int)$program_id)->row();

		if($vendor_id){

			if((int)$old->id == 0){
				// Add Default Admin Settings
				$market_vendor = $this->Product_model->getSettings('market_vendor');
		
				$program['admin_click_status'] = $market_vendor['click_status'];
				$program['admin_commission_click_commission'] = $market_vendor['commission_click_commission'];
				$program['admin_commission_number_of_click'] = $market_vendor['commission_number_of_click'];
				$program['admin_sale_status'] = $market_vendor['sale_status'];
				$program['admin_commission_type'] = $market_vendor['commission_type'];
				$program['admin_commission_sale'] = $market_vendor['commission_sale'];
			}

			$program['commission_type'] = $data['commission_type'];
			$program['commission_sale'] = $data['commission_sale'];
			$program['sale_status'] = $data['sale_status'];
			$program['commission_number_of_click'] = $data['commission_number_of_click'];
			$program['commission_click_commission'] = $data['commission_click_commission'];
			$program['click_status'] = $data['click_status'];

			if($old){
				if(
					$old->commission_type != $data['commission_type'] ||
					$old->commission_sale != $data['commission_sale'] ||
					$old->sale_status != $data['sale_status'] ||
					$old->commission_number_of_click != $data['commission_number_of_click'] ||
					$old->commission_click_commission != $data['commission_click_commission'] ||
					$old->click_status != $data['click_status']
				){
					$program['status'] = 0;
				}
			}
		} else {
			$program['admin_commission_type'] = $data['admin_commission_type'];
			$program['admin_commission_sale'] = $data['admin_commission_sale'];
			$program['admin_sale_status'] = $data['admin_sale_status'];
			$program['admin_commission_number_of_click'] = $data['admin_commission_number_of_click'];
			$program['admin_commission_click_commission'] = $data['admin_commission_click_commission'];
			$program['admin_click_status'] = $data['admin_click_status'];
			$program['status'] = (int)$data['status'];
		}

		if((int)$old->vendor_id == 0){
			$program['status'] = 1;

			$program['commission_type'] = $data['commission_type'];
			$program['commission_sale'] = $data['commission_sale'];
			$program['sale_status'] = $data['sale_status'];
			$program['commission_number_of_click'] = $data['commission_number_of_click'];
			$program['commission_click_commission'] = $data['commission_click_commission'];
			$program['click_status'] = $data['click_status'];
		}

		if (isset($data['comment']) && trim($data['comment'])) {
            $comment = json_decode($old->comment,1);
            $comment[] = [
                'from'    => $vendor_id ? 'admin' : 'affiliate',
                'comment' => $data['comment'],
            ];
            $program['comment'] = json_encode($comment);
		}

		$this->load->model('Mail_model');

		if($program_id > 0){
			$this->db->update("integration_programs",$program,['id' => $program_id]);
			if(isset($program['status']) && $program['status'] != $old->status){
				if($editBy == 'admin'){
					$this->Mail_model->vendor_program_status_change($program_id, 'vendor', true);
				} else{
					$this->Mail_model->vendor_program_status_change($program_id, 'admin', true);
				}
			}
		} else {
			$program['vendor_id'] = (int)$vendor_id;

			if((int)$program['vendor_id']){
				$program['status'] = 0;
			}

			$this->db->insert("integration_programs",$program);
			$program_id = $this->db->insert_id();

			if($vendor_id){
				$this->Mail_model->vendor_create_program($program_id);
			}
		}

		return $program_id;
    }

    public function getProgramToolsByID($id){
    	$data = $this->db->select("integration_tools.*,users.username,users.email, CONCAT(users.firstname,' ',users.lastname) as vendor_name")
    			->from("integration_tools")
    			->join('users','users.id = integration_tools.vendor_id','left')
    			->where("integration_tools.id",(int)$id)
    			->get()
    			->row_array();

    	$data['ads'] = array();
    	if($data){
    		$data['ads'] = $this->getAds($data['id']);

    		if($data['type'] == 'video_ads' && isset($data['ads'][0])){
    			$_video = $data['ads'][0];
    			$data['ads'][0]['video_type'] = $videoType = $this->videoType($_video['value']);

    			$height = $_video['video_height'] ."px";
				$width = $_video['video_width'] ."px";
				$autoplay = isset($_video['autoplay']) && $_video['autoplay'] ? 1 : 0 ;

    			if($videoType == 'youtube'){
    				preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $_video['value'], $matches);
					$id = $matches[1];

					$data['ads'][0]['iframe'] = '<iframe class="dt-youtube" width="'. $width .'" height="'. $height .'" src="//www.youtube.com/embed/'.$id.'?autoplay='. $autoplay .'" frameborder="0" allow="'. ($autoplay ? 'autoplay;' : '') .' fullscreen" allowfullscreen></iframe>';
    			} else if($videoType == 'vimeo'){
    				preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im',$_video['value'], $matches);
    				
					$id = $matches[3];			
					$data['ads'][0]['iframe'] = '<iframe src="//player.vimeo.com/video/'.$id.'?autoplay='. $autoplay .'title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff" width="'. $width .'" height="'. $height .'" frameborder="0" allow="'. ($autoplay ? 'autoplay;' : '') .' fullscreen" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    			}
    		}

    		$data['commission'] = json_decode($data['commission'], 1);
    	}
    
    	return $data;
    }

    private function videoType($url) {
	    if (strpos($url, 'youtube') > 0 || strpos($url, 'youtu.be') > 0) {
	        return 'youtube';
	    } elseif (strpos($url, 'vimeo') > 0) {
	        return 'vimeo';
	    } else {
	        return 'unknown';
	    }
		
    }

    public function addParams($url, $key, $value) {
		$url = preg_replace('/(.*)(?|&)'. $key .'=[^&]+?(&)(.*)/i', '$1$2$4', $url .'&');
		$url = substr($url, 0, -1);
		
		if (strpos($url, '?') === false) {
			return ($url .'?'. $key .'='. $value);
		} else {
			return ($url .'&'. $key .'='. $value);
		}
	}

    public function getProgramTools($filter = array()){
    	$where = ' 1 ';
    	if(isset($filter['user_id'])){
    		$where = ' user_id = '. $filter['user_id'];
    	}

    	//(SELECT count(*) FROM `integration_clicks_action` WHERE {$where} AND is_action  = 1 AND integration_clicks_action.tools_id = `integration_tools`.id ) as total_action_click_count,

    	$query = $this->db->select("
    			SQL_CALC_FOUND_ROWS integration_tools.*,
    			users.username,
    			integration_programs.commission_type,
    			integration_programs.commission_sale,
    			integration_programs.commission_number_of_click,
    			integration_programs.commission_click_commission,
    			integration_programs.click_status,
    			integration_programs.sale_status,

				integration_programs.admin_commission_type,
    			integration_programs.admin_commission_sale,
    			integration_programs.admin_commission_number_of_click,
    			integration_programs.admin_commission_click_commission,
    			integration_programs.admin_click_status,
    			integration_programs.admin_sale_status,

    			integration_programs.name as program_name,
    			(SELECT sum(amount) FROM `wallet` WHERE {$where} AND wallet.reference_id = `integration_tools`.id AND wallet.type = 'sale_commission' AND wallet.comm_from = 'ex' AND wallet.status > 0 ) as total_sale_amount,
    			(SELECT count(*) FROM `integration_orders` WHERE {$where} AND integration_orders.ads_id = `integration_tools`.id ) as total_sale_count,
    			(SELECT sum(amount) FROM `wallet` WHERE {$where} AND wallet.reference_id = `integration_tools`.id AND wallet.type = 'external_click_commission' AND wallet.comm_from = 'ex'  AND reference_id_2 IN ('')) as total_click_amount,

    			(SELECT sum(amount) FROM `wallet` WHERE {$where} AND wallet.reference_id = `integration_tools`.id AND wallet.type = 'external_click_commission' AND wallet.comm_from = 'ex'  AND reference_id_2 IN ('__general_click__')) as total_general_click_amount,

    			(SELECT sum(amount) FROM `wallet` WHERE {$where} AND wallet.reference_id = `integration_tools`.id AND wallet.type = 'external_click_commission' AND wallet.comm_from = 'ex' AND status > 0 AND wallet.is_action = 1 ) as total_action_click_amount,
    			
    			(SELECT count(*) FROM `integration_clicks_action` WHERE {$where} AND action_code IN ('_af_product_click') AND integration_clicks_action.tools_id = `integration_tools`.id ) as total_click_count,



    			(SELECT count(amount) FROM `wallet` WHERE {$where} AND wallet.reference_id = `integration_tools`.id AND wallet.type = 'external_click_commission' AND wallet.comm_from = 'ex' AND status > 0 AND wallet.is_action = 1 ) as total_action_click_count,

    			(SELECT count(*) FROM `integration_clicks_action` WHERE {$where} AND is_action  = 0 AND page_name != '' AND integration_clicks_action.tools_id = `integration_tools`.id ) as total_general_click_count

			",FALSE)
    		->from("integration_tools")
    		->join('integration_programs','integration_tools.program_id = integration_programs.id','left')
    		->order_by("integration_tools.id","DESC");
    		
    	$query->join("users","users.id=integration_tools.vendor_id","left");

    	if (isset($filter['restrict'])) {
    		$query->where("(FIND_IN_SET(". (int)$filter['restrict'] .", integration_tools.allow_for) OR integration_tools.allow_for = '' OR integration_tools.allow_for IS NULL )");
    	}
    	if (isset($filter['category_id']) && (int)$filter['category_id']) {
    		$query->where("FIND_IN_SET(". (int)$filter['category_id'] .", integration_tools.category)");
    	}
    	if (isset($filter['status'])) {
    		$query->where("integration_tools.status",$filter['status']);
    	}
    	if (isset($filter['vendor_id'])) {
    		$query->where("integration_tools.vendor_id",$filter['vendor_id']);
    	}
    	if (isset($filter['not_show_my'])) {
    		$query->where("(integration_tools.vendor_id IS NULL OR integration_tools.vendor_id != ". (int)$filter['not_show_my'].")");
    	}
    	if (isset($filter['ads_name']) && $filter['ads_name']) {
    		$query->where("integration_tools.name like '". $filter['ads_name'] ."%' ");
    	}
    	if (isset($filter['show_only']) && $filter['show_only'] == 'admin') {
    		$query->where("integration_tools.vendor_id IS NULL OR integration_tools.vendor_id = 0 ");
    	}
    	$start = 0;
    	if (isset($filter['start'])){
    		$start = (int)$filter['start'];
    	}
    	if (isset($filter['limit'])) {
    		$query->limit($filter['limit'], $start);
    	}

    	if (isset($filter['page'],$filter['limitdata'])) {
            $offset = (($filter['page']-1) * $filter['limitdata']);
            $query->limit($filter['limitdata'],$offset);
        }

    	$data = array();
    	$query = $query->get()->result_array();

    	if(isset($filter['page'])){
    		$total = $this->db->query("SELECT FOUND_ROWS() AS total")->row()->total;
    	}

    	foreach ($query as $key => $value) {
    		$redirectLocation = [];

    		if(isset($filter['redirectLocation'])){
    			$tools = $this->getAds($value['id'] , $filter);

    			foreach ($tools as $_value) {
    				$redirectLocation[] = $this->addParams($value['target_link'],"af_id",_encrypt_decrypt($filter['user_id']."-".$_value['id']));
    			}
    		}
    		
    		$data[] = array(
				'id'                                => $value['id'],
				'redirectLocation'                  => $redirectLocation,
				'program_id'                        => $value['program_id'],
				'name'                              => $value['name'],
				'vendor_id'                              => $value['vendor_id'],
				'program_name'                      => $value['program_name'],
				'target_link'                       => $value['target_link'],
				'status'                            => $value['status'],
				'action_click'                      => $value['action_click'],
				'action_amount'                     => $value['action_amount'],
				'general_click'                     => $value['general_click'],
				'general_amount'                    => $value['general_amount'],

				'admin_action_click'                      => $value['admin_action_click'],
				'admin_action_amount'                     => $value['admin_action_amount'],
				'admin_general_click'                     => $value['admin_general_click'],
				'admin_general_amount'                    => $value['admin_general_amount'],


				'_tool_type'                        => $value['tool_type'],
				'type'                              => ucfirst( str_replace("_", " ", $value['type'])),
				'_type'                             => $value['type'],
				'commission_type'                   => $value['commission_type'],
				'commission_sale'                   => $value['commission_sale'],
				'commission_number_of_click'        => $value['commission_number_of_click'],
				'commission_click_commission'       => $value['commission_click_commission'],
				'click_status'                      => $value['click_status'],
				'sale_status'                       => $value['sale_status'],
				'admin_commission_type'             => $value['admin_commission_type'],
				'admin_commission_sale'             => $value['admin_commission_sale'],
				'admin_commission_number_of_click'  => $value['admin_commission_number_of_click'],
				'admin_commission_click_commission' => $value['admin_commission_click_commission'],
				'admin_click_status'                => $value['admin_click_status'],
				'admin_sale_status'                 => $value['admin_sale_status'],
				'recursion'                         => $value['recursion'],
				'recursion_custom_time'             => $value['recursion_custom_time'],
				'username'                          => $value['username'],
				'recursion_endtime'                 => $value['recursion_endtime'],
				'featured_image'                    => $value['featured_image'],
				
				'total_sale_amount'                 => c_format($value['total_sale_amount']),
				'total_click_amount'                => c_format($value['total_click_amount']),
				'total_action_click_amount'         => c_format($value['total_action_click_amount']),
				'total_general_click_amount'        => c_format($value['total_general_click_amount']),
				'total_sale_count'                  => (int)$value['total_sale_count'],
				'total_click_count'                 => (int)$value['total_click_count'],
				'total_action_click_count'          => (int)$value['total_action_click_count'],
				'total_general_click_count'         => (int)$value['total_general_click_count'],
				'tool_type'                         => ucfirst( str_replace("_", " ", $value['tool_type'])),
				'created_at'                        => date("d-m-Y h:i A",strtotime($value['created_at'])),
				'product_created_date'              => date("d-m-Y h:i A",strtotime($value['created_at'])),
				'is_tool'                           => 1,
    		);	
    	}
    	if(isset($filter['page'])){
    		return [$data,$total];
    	}
    	return $data;
    }

    public function getAds($tools_id, $filter = array()){
    	$where = '';

    	if (isset($filter['restrict'])) {
    		//$where .= " AND (FIND_IN_SET(". (int)$filter['restrict'] .", integration_tools_ads.allow_for) OR integration_tools_ads.allow_for = '' OR integration_tools_ads.allow_for IS NULL )  ";
    	}
    	$query = $this->db->query("SELECT * FROM integration_tools_ads WHERE tools_id = {$tools_id} {$where}")->result_array();


    	$data = array();

    	foreach ($query as $key => $value) {
    		$v = $value['value'];
    		if($value['ads_type'] == 'banner'){
    			$v = base_url("assets/integration/uploads/{$tools_id}/".$value['value']);
    		}


    		$d = json_decode($value['data'],1);
			$d['id']       = $value['id'];
			$d['tools_id'] = $value['tools_id'];
			$d['ads_type'] = $value['ads_type'];
			$d['value']    = $v;
			$d['size']     = $value['size'];
			//$d['allow_for']     = $value['allow_for'];

    		$data[] = $d;
    	}
    	
    	return $data;
    }

    public function editProgramTools($data,$files = array(), $editBy = 'admin', $vendor_id = 0){
    	$allow_for = '';
    	if($data['allow_for_radio'] == '1'){
    		$allow_for  = isset($data['allow_for']) ? implode(",", $data['allow_for']) : '0';
    	}

    	$commission = array();
    	if($data['commission_type'] == 'custom'){
    		$commission = array('referlevel' => $data['referlevel']);
    		$setting = $this->Product_model->getSettings('referlevel');
			$max_level = isset($setting['levels']) ? (int)$setting['levels'] : 3;
			for ($i=1; $i <= $max_level; $i++) { 
				$commission['referlevel_'. $i] = $data['referlevel_'.$i];
			}
    	}

    	$recursion_custom_time = ($data['recursion'] == 'custom_time' ) ? $data['recursion_custom_time'] : 0;

    	$program = array(
			'name'                  => $data['name'],
			'program_id'            => $data['program_id'],
			'target_link'           => $data['target_link'],
			'type'                  => $data['type'],
			'tool_type'             => $data['tool_type'],
			'action_code'           => $data['action_code'],
			'general_code'          => $data['general_code'],
			'commission_type'       => $data['commission_type'],
			'featured_image'        => $data['featured_image'],
			'allow_for'             => $allow_for,
			'commission'            => json_encode($commission),
			'marketpostback'        => json_encode($data['marketpostback']),
			'category'              => implode(",", (isset($data['category']) ? $data['category'] : [])),
			'recursion'             => $data['recursion'],
			'recursion_endtime'     => (isset($data['recursion_endtime_status']) && $data['recursion_endtime']) ? date("Y-m-d H:i:s",strtotime($data['recursion_endtime'])) : null,
			'recursion_custom_time' => (int)$recursion_custom_time,
		);

    	$program_tool_id = isset($data['program_tool_id']) ? (int)$data['program_tool_id'] : 0;
    	$old = $this->db->query("SELECT * FROM integration_tools WHERE id=". (int)$program_tool_id)->row();
		if($vendor_id){
			$program['action_click']   = (float)$data['action_click'];
			$program['action_amount']  = (float)$data['action_amount'];
			$program['general_click']  = (float)$data['general_click'];
			$program['general_amount'] = (float)$data['general_amount'];

			if($old){
				if(
					$old->action_click != $data['action_click'] ||
					$old->action_amount != $data['action_amount'] ||
					$old->general_click != $data['general_click'] ||
					$old->general_amount != $data['general_amount']
				){
					$program['status'] = 0;
				}
			}
		} else {
			$program['admin_action_click']   = (float)$data['admin_action_click'];
			$program['admin_action_amount']  = (float)$data['admin_action_amount'];
			$program['admin_general_click']  = (float)$data['admin_general_click'];
			$program['admin_general_amount'] = (float)$data['admin_general_amount'];
			$program['status'] = (int)$data['status'];
		}

		if((int)$old->vendor_id == 0){
			$program['status'] = isset($data['status']) ? (int)$data['status'] : 1;

			$program['action_click']   = (float)$data['action_click'];
			$program['action_amount']  = (float)$data['action_amount'];
			$program['general_click']  = (float)$data['general_click'];
			$program['general_amount'] = (float)$data['general_amount'];
		}

		if (isset($data['comment']) && trim($data['comment'])) {
            $comment = json_decode($old->comment,1);
            $comment[] = [
                'from'    => $vendor_id ? 'admin' : 'affiliate',
                'comment' => $data['comment'],
            ];
            $program['comment'] = json_encode($comment);
		}

		$this->load->model('Mail_model');
		if($program_tool_id > 0){
			$this->db->update("integration_tools",$program,['id' => $program_tool_id]);

			if(isset($program['status']) && $program['status'] != $old->status){
				if($editBy == 'admin'){
					$this->Mail_model->vendor_ads_status_change($program_tool_id, 'vendor', true);
				} else{
					$this->Mail_model->vendor_ads_status_change($program_tool_id, 'admin', true);
				}
			}

		} else {
			if($editBy == 'vendor'){
				$program['status'] = 0;
			}
			
			$program['vendor_id'] = (int)$vendor_id;
			$this->db->insert("integration_tools",$program);
			$program_tool_id = $this->db->insert_id();

			if($vendor_id){
				$this->Mail_model->vendor_create_ads($program_tool_id);
			}
		}

		if($data['type'] == 'banner'){
			$data_ads = array();
			$keep_ads = isset($data['keep_ads']) ? $data['keep_ads'] : array();
			if($files){
				$base_path = "assets/integration/uploads/{$program_tool_id}/";
				if (!file_exists($base_path)) { mkdir($base_path, 0777, true); }

				foreach ($files['name'] as $index => $name) {
					if($files['error'][$index] == 0){
						$ext = pathinfo($name, PATHINFO_EXTENSION);
						if($ext=='jpg' || $ext=='jpeg' || $ext=='png' || $ext=='gif'){
							$filename = time().rand(11111,99999).".{$ext}";
							move_uploaded_file($files['tmp_name'][$index], $base_path.$filename);

							$data_ads[] = array(
								'tools_id' => $program_tool_id,
								'ads_type' => 'banner',
								'value'    => $filename,
								'size'     => isset($data['custom_banner_size'][$index]) ? $data['custom_banner_size'][$index] : '',
							);
						}
					}
				}
			}

			foreach ($data_ads as $key => $value) {
				$this->db->insert("integration_tools_ads",$value);
				$keep_ads[] = $this->db->insert_id();
			}

			if($keep_ads){
				$this->db->query("DELETE FROM integration_tools_ads WHERE id NOT IN(". implode(",", $keep_ads) .") AND tools_id={$program_tool_id} ");
				$this->db->query("UPDATE integration_tools_ads SET tools_id={$program_tool_id} WHERE id IN(". implode(",", $keep_ads) .")  ");
			}
		} else if($data['type'] == 'text_ads'){
			$check = $this->db->query("SELECT id FROM integration_tools_ads WHERE tools_id = ". $program_tool_id)->row();
			$extra = array(
				'text_color'        => $data['text_color'],
				'text_bg_color'     => $data['text_bg_color'],
				'text_border_color' => $data['text_border_color'],
				'text_size'         => $data['text_size'],
			);
			$data_ads = array(
				'tools_id' => $program_tool_id,
				'ads_type' => 'text_ads',
				'value'    => $data['text_ads_content'],
				'size'     => '',
				'data'     => json_encode($extra),
			);

			if(!$check){
				$this->db->insert("integration_tools_ads",$data_ads);
			} else{
				$this->db->update("integration_tools_ads",$data_ads,['id' => $check->id]);
			}
		} else if($data['type'] == 'link_ads'){
			$check = $this->db->query("SELECT id FROM integration_tools_ads WHERE tools_id = ". $program_tool_id)->row();
			$data_ads = array(
				'tools_id'  => $program_tool_id,
				'ads_type'  => 'link_ads',
				'value'     => $data['link_title'],
				'size'      => '',
			);

			if(!$check){
				$this->db->insert("integration_tools_ads",$data_ads);
			} else{
				$this->db->update("integration_tools_ads",$data_ads,['id' => $check->id]);
			}
		} else if($data['type'] == 'video_ads'){
			$check = $this->db->query("SELECT id FROM integration_tools_ads WHERE tools_id = ". $program_tool_id)->row();

			$extra = array(
				'video_height' => $data['video_height'],
				'video_width'  => $data['video_width'],
				'autoplay'     => $data['autoplay'],
			);

			$data_ads = array(
				'tools_id' => $program_tool_id,
				'ads_type' => 'video_ads',
				'value'    => $data['video_link'],
				'size'     => $data['button_text'],
				'data'     => json_encode($extra),
			);

			if(!$check){
				$this->db->insert("integration_tools_ads",$data_ads);
			} else{
				$this->db->update("integration_tools_ads",$data_ads,['id' => $check->id]);
			}
		}

		return $program_tool_id;
    }

    public function deleteTools($program_tool_id){
    	$this->db->query("DELETE FROM integration_tools_ads WHERE tools_id={$program_tool_id} ");
    	$this->db->query("DELETE FROM integration_tools WHERE id={$program_tool_id} ");

    	$path = "assets/integration/uploads/{$program_tool_id}/";
    	$this->cart->delete_directory($path);
    }

    public function getDeleteOrders($ids) {
    	$ids = explode(",", $ids);

    	$data = array();
    	foreach ($ids as $key => $id) {
    		$data[$id]['commission'] = $this->db->query("SELECT sum(amount) as total FROM wallet WHERE type IN('sale_commission','admin_sale_commission') AND reference_id_2 = {$id} ")->row()->total;
    		$data[$id]['refer_commission'] = $this->db->query("SELECT sum(amount) as total FROM wallet WHERE type = 'refer_sale_commission' AND comm_from = 'ex' AND reference_id_2 = {$id} ")->row()->total;
    		$data[$id]['sql'][] = "DELETE FROM integration_orders WHERE id = {$id} ";
    		$data[$id]['sql'][] = "DELETE FROM wallet WHERE type IN('sale_commission','sale_commission_vendor_pay','admin_sale_commission','admin_sale_commission_v_pay','refer_sale_commission') AND reference_id_2 = {$id}";
    		//$data[$id]['sql'][] = "DELETE FROM wallet WHERE type = 'refer_sale_commission' AND comm_from = 'ex' AND reference_id_2 = {$id}";
    	}

    	return $data;
    }

    public function getOrders($filter = array()) {
    	
    	$query = $this->db->select("integration_orders.*,CONCAT(users.firstname,' ',users.lastname) as user_name");
    	$query->from("integration_orders");
    	$query->join("users","integration_orders.user_id = users.id");

    	if(isset($filter['user_id'])){
    		//$query->where("integration_orders.user_id", (int)$filter['user_id']);
    		$query->where("(integration_orders.user_id=". (int)$filter['user_id'] ." OR integration_orders.vendor_id=". (int)$filter['user_id'] .")");
    	}
    	if(isset($filter['vendor_id'])){
    		$query->where("integration_orders.vendor_id", (int)$filter['vendor_id']);
    	}
    	if(isset($filter['id_gt'])){
    		$query->where("integration_orders.id > ". (int)$filter['id_gt']);
    	}
    	if(isset($filter['limit'])){
    		$query->limit( (int)$filter['limit']);
    	}

    	$query = $this->db->order_by('integration_orders.id','DESC')->get()->result();
    	$data = array();

    	foreach ($query as $key => $value) {
    		$data[] = array(
				'id'              => $value->id,
				'order_id'        => $value->order_id,
				'product_ids'     => $value->product_ids,
				'total'           => $value->total,
				'currency'        => $value->currency,
				'user_id'         => $value->user_id,
				'commission_type' => $value->commission_type,
				'commission'      => $value->commission,
				'ip'              => $value->ip,
				'country_code'    => $value->country_code,
				'base_url'        => $value->base_url,
				'ads_id'          => $value->ads_id,
				'script_name'     => $value->script_name,
				'created_at'      => date("d-m-Y h:i A", strtotime($value->created_at)),
				'user_name'       => $value->user_name,
    		);
    	}

    	return $data;
    }

    public function getOrder($id) {
    	
    	$query = $this->db->select("integration_orders.*,CONCAT(users.firstname,' ',users.lastname) as user_name,users.email");
    	$query->from("integration_orders");
    	$query->join("users","integration_orders.user_id = users.id");
    	$query->where("integration_orders.id",(int)$id);
    	$value = $this->db->get()->row();

    	if($value){
			$data = array(
				'id'              => $value->id,
				'order_id'        => $value->order_id,
				'product_ids'     => $value->product_ids,
				'total'           => $value->total,
				'currency'        => $value->currency,
				'user_id'         => $value->user_id,
				'commission_type' => $value->commission_type,
				'commission'      => $value->commission,
				'ip'              => $value->ip,
				'country_code'    => $value->country_code,
				'base_url'        => $value->base_url,
				'ads_id'          => $value->ads_id,
				'script_name'     => $value->script_name,
				'created_at'      => date("d-m-Y h:i A", strtotime($value->created_at)),
				'user_name'       => $value->user_name,
				'email'       => $value->email,
			);
	    	

	    	return $data;
    	}

    	return false;
    	
    }

    public function getLogs($filter = array()){
    	$data = array(
    		'records' => array(),
    		'total' => 0,
    	);

    	$query = $this->db->from('integration_clicks_logs');
    	$query->join("users","users.id=integration_clicks_logs.user_id","left");

    	if(isset($filter['user_id'])){
    		$query->where("integration_clicks_logs.user_id", (int)$filter['user_id']);
    	}
    	if(isset($filter['type'])){
    		$query->where("integration_clicks_logs.click_type", $filter['type']);
    	}

    	if(isset($filter['id_gt'])){
    		$query->where("integration_clicks_logs.id > ". (int)$filter['id_gt']);
    	}

    	$total_query = clone $query;
    	$query = $query->order_by('integration_clicks_logs.id','DESC');

    	$limit = isset($filter['limit']) ? $filter['limit'] : 50;

    	if(isset($filter['page'])){ $query->limit($limit, ( ($filter['page']-1) * $limit) ); }


    	$data['total'] = $total_query->select("COUNT(integration_clicks_logs.id) as total")->get()->row()->total;
    	$query = $query->select("integration_clicks_logs.*,CONCAT(users.firstname,' ',users.lastname) as username")->get()->result_array();

    	foreach ($query as $key => $value) {
    		$data['records'][] = array(
				'id'             => $value['id'],
				'base_url'       => $value['base_url'],
				'link'           => $value['link'],
				'agent'          => $value['agent'],
				'browserName'    => $value['browserName'],
				'browserVersion' => $value['browserVersion'],
				'systemString'   => $value['systemString'],
				'osPlatform'     => $value['osPlatform'],
				'osVersion'      => $value['osVersion'],
				'osShortVersion' => $value['osShortVersion'],
				'isMobile'       => $value['isMobile'],
				'mobileName'     => $value['mobileName'],
				'osArch'         => $value['osArch'],
				'isIntel'        => $value['isIntel'],
				'isAMD'          => $value['isAMD'],
				'isPPC'          => $value['isPPC'],
				'ip'             => $value['ip'],
				'country_code'   => $value['country_code'],
				'created_at'     => date("d-m-Y h:i A",strtotime($value['created_at'])),
				'click_id'       => $value['click_id'],
				'username'       => $value['username'],
				'click_type'     => str_replace("_", " ", ucfirst($value['click_type'])),
				'flag'           => "<img class='small-flag' title='". $value['country_code'] ."' src='". base_url('assets/vertical/assets/images/flags/'. strtolower($value['country_code'])) .".png'>",
    		);
    	}
    	 
    	return $data;
    }
}