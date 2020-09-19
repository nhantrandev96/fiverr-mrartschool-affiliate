<?php
class Imoprt_user extends MY_Model{
    function import($user, $_custom_fields){
    	$direct_field = array(
			'email',
			'username',
			'firstname',
			'lastname',
			'Country',
			'refid',
		);

        $found_user = $this->db->query("SELECT id FROM users WHERE email like '". $user['email'] ."' ")->row_array();
        $userData = array();
        $user_errors = array();

        $user['user_id'] = 0;
        if($found_user){
        	$user['user_id'] = $found_user['id'];
    	}

    	$custom_fields = array();
    	foreach ($_custom_fields as $key => $value) { 
    		if($value['type'] == 'header') continue;

    		$custom_fields[$value['name']] = $value;
    	}
    	 
    	foreach ($direct_field as $field_name) {
    		switch ($field_name) {
    			case 'Country':
    				$user['Country'] = $this->getCountryId($user['sortname']);
    				$userData['ucountry'] = $user['Country'];
    				if($user['Country'] == 0){
    					$user_errors[] = "Invalid Country Name";
    				}
    				break;
				case 'firstname':
    				if($user['firstname'] == ''){
					  	$user_errors[] = "Enter First Name";
    				}
    				break;
				case 'lastname':
    				if($user['lastname'] == ''){
					  	$user_errors[] = "Enter Last Name";
    				}
    				break;
				case 'email':
    				if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
					  	$user_errors[] = "Invalid email address";
					} else {
						if($this->checkUniqueUserEmail($user['email'], (int)$user['user_id'])){
					  		$user_errors[] = "Duplicate email address";
						}
					}
    				break;
				case 'username':
    				if ($user['username'] == '') {
					  	$user_errors[] = "Username is required";
					} else {
						if($this->getUserIdByusername($user['username'], (int)$user['user_id'])){
					  		$user_errors[] = "Duplicate username";
						}
					}
    				break;
				case 'refid':
					$user['refid'] = 0;
    				if($user['under_affiliate'] != ''){
						$user['refid'] = $this->getUserIdByusername($user['under_affiliate'], (int)$user['user_id']);
					}
    				break;
    			default:
    				
    				
    				break;
    		}

    		$userData[$field_name] = isset($user[$field_name]) ? $user[$field_name] : '';
    	}

    	$custom_values = array();
    	foreach ($custom_fields as $key => $_value) {
    		$field_name = $_value['name'];

			if($_value['required'] == 'true'){
				if(!isset($user[$field_name]) || $user[$field_name] == ''){
					$user_errors[] = $_value['label'] ." is required.!";
				}
			}
			else if((int)$_value['maxlength'] > 0){
				if(strlen( $user[$field_name] ) > (int)$_value['maxlength']){
					$user_errors[] = $_value['label'] ." Maximum length is ". (int)$_value['maxlength'];
				}
			}
			else if((int)$_value['minlength'] > 0){
				if(strlen( $user[$field_name] ) > (int)$_value['minlength']){
					$user_errors[] = $_value['label'] ." Minimum length is ". (int)$_value['minlength'];
				}
			}
			else if($_value['mobile_validation']  == 'true'){
				/*if(!preg_match('/^[0-9]{10}+$/', $user[$field_name])){
					$user_errors[] = $_value['label'] ." Invalid mobile number ";
				}*/
			}

			$custom_values['custom_'.$_value['name']] = $user[$field_name];
    	}
        
    	if($user['user_id'] == 0 && $user['password'] == ''){
    		$user_errors[] = 'Password is required for new user';
    	}

    	$status = false;
		$return_message = '';

    	if(empty($user_errors)){
	    	if($user['password'] != ''){ $userData['password'] = sha1($user['password']); }

	    	$userData['type'] = 'user';
			$userData['value'] = json_encode($custom_values);

	    	if($found_user){	
	    		$this->db->where('id',$found_user['id']);
	    		$userData['updated_at'] = date("Y-m-d H:i:s");
				$this->db->update('users',$userData);

				$userData['user_id'] = $found_user['id'];
				$return_message = 'Updated User';
	    	} else {
	    		$null_user = $this->null_user();
	    		$userData = array_merge($null_user,$userData);
	    		$this->db->insert('users',$userData);
	    		$userData['user_id'] = $this->db->insert_id();
				$return_message = 'Insert New User';
	    	}

	    	$this->updatePaymentDetails($user, $userData['user_id']);

	    	$status = true;
    	} else {
    		$return_message = implode("<br>", $user_errors);
    	}

    	return "<li class='". ($status ? 'import-success' : 'impoer-error') ."'>{$return_message}</li>";
    }

    private function getCountryId($sortname){
        return (int)$this->db->query("SELECT id FROM countries WHERE sortname like '{$sortname}' ")->row_array()['id'];
    }
    private function checkUniqueUserEmail($email, $skip_id){
        return (int)$this->db->query("SELECT id FROM users WHERE id != {$skip_id} AND email like '{$email}' ")->row_array()['id'];
    }
    private function getUserIdByusername($username, $skip_id){
        return (int)$this->db->query("SELECT id FROM users WHERE id != {$skip_id} AND username like '{$username}' ")->row_array()['id'];
    }

    private function updatePaymentDetails($userData, $user_id){

    	if($userData['paypal_email'] != ''){
	    	$email = $userData['paypal_email'];
	    	$paypal_id = (int)$this->db->query("SELECT id FROM paypal_accounts WHERE user_id = {$user_id}")->row_array()['id'];

			if ($paypal_id > 0) {
				$this->db->update("paypal_accounts", array(
					'paypal_email' => $email,
				),array('id' => $paypal_id));
			}else{
				$this->db->insert("paypal_accounts", array(
					'paypal_email' => $email,
					'user_id' => $user_id,
				));
			}
    	}

    	$details = array(
			'payment_bank_name'      =>  $userData['payment_bank_name'],
			'payment_account_number' =>  $userData['payment_account_number'],
			'payment_account_name'   =>  $userData['payment_account_name'],
			'payment_ifsc_code'      =>  $userData['payment_ifsc_code'],
			'payment_created_by'     =>  $user_id,
			'payment_status'         =>  1,
			'payment_ipaddress'      =>  '0.0.0.0',
		);

		if(
			$userData['payment_bank_name'] != '' &&
			$userData['payment_account_number'] != '' &&
			$userData['payment_account_name'] != '' &&
			$userData['payment_ifsc_code'] != ''
		){
			$payment_id = (int)$this->db->query("SELECT payment_id FROM payment_detail WHERE payment_created_by = {$user_id}")->row_array()['payment_id'];

			if( (int)$payment_id > 0 ){
				$details['payment_updated_date'] = date('Y-m-d H:i:s');
				$this->Product_model->update_data('payment_detail', $details,array('payment_id' => (int)$payment_id));	
			}
			else {
				$details['payment_created_date'] = date('Y-m-d H:i:s');
				$this->db->insert("payment_detail", $details);
			}
		}

    }

    private function null_user(){
    	return array(
			'firstname'                 => '',
			'lastname'                  => '',
			'email'                     => '',
			'username'                  => '',
			'password'                  => '',
			'refid'                     => '',
			'type'                      => '',
			'Country'                   => '',
			'City'                      => '',
			'phone'                     => '',
			'twaddress'                 => '',
			'address1'                  => '',
			'address2'                  => '',
			'ucity'                     => '',
			'ucountry'                  => '',
			'state'                     => '',
			'uzip'                      => '',
			'avatar'                    => '',
			'online'                    => '0',
			'unique_url'                => '',
			'bitly_unique_url'          => '',
			'created_at'                => date("Y-m-d H:i:s"),
			'updated_at'                => date("Y-m-d H:i:s"),
			'google_id'                 => '',
			'facebook_id'               => '',
			'twitter_id'                => '',
			'umode'                     => '',
			'PhoneNumber'               => '',
			'Addressone'                => '',
			'Addresstwo'                => '',
			'StateProvince'             => '',
			'Zip'                       => '',
			'f_link'                    => '',
			't_link'                    => '',
			'l_link'                    => '',
			'product_commission'        => '0',
			'affiliate_commission'      => '0',
			'product_commission_paid'   => '0',
			'affiliate_commission_paid' => '0',
			'product_total_click'       => '0',
			'product_total_sale'        => '0',
			'affiliate_total_click'     => '0',
			'sale_commission'           => '0',
			'sale_commission_paid'      => '0',
			'status'                    => '1',
			'value'                     => '',
		);
    }
}
