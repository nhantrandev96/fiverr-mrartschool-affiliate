<?php

if($unique_id == 'send_register_mail_api'){
    $user_text = '<p>Welcome to [[website_name]]</p>
                <p>Dear [[firstname]],</p>
                <p>Thanks for signing up [[website_name]].</p>
                <p>Your&nbsp;Login&nbsp;credentials:</p>
                <p>Username:&nbsp;<strong>[[username]]</strong><br />
                Password:&nbsp;<strong>*******</strong></p>
                <p>&nbsp;</p>
                <p><a href="[[website_url]]">Login To [[website_name]]</a></p>

                <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';


    $admin_text = '<p>Dear Admin,</p>
        <p>New affiliate user Register on your site [[website_name]]</p>
        <p>Affiliate details:</p>

        <p>============</p>
        <p>[[firstname]]</p>
        <p>[[username]]</p>
        <p>[[email]]</p>
        <p><br />
        [[website_name]]<br />
        Support Team</p>
    ';

    $data = array(
        'unique_id'     => $unique_id,
        'name'          => 'User Registration (API)',
        'text'          => $user_text,
        'admin_text'    => $admin_text,
        'subject'       => 'Your Account Created Successfully On [[website_name]]',
        'admin_subject' => 'User Registration Successfully',
        'shortcode'     => 'firstname,lastname,email,username,website_url,website_name,website_logo,name',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'withdrwal_status_change'){
    $admin_text = '<p>Dear,</p>
                <p>Your Withdrawal Request #([[request_id]]) Status has been change to <b><i>[[status]]</i></b></p>

                    <p>Comment: [[comment]] </p>
                [[website_name]]<br />
                Support Team</p>
            ';
    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'name'          => 'Withdrawal Request Status Changed',
        'subject'       => 'Withdrawal Request Status Changed',
        'admin_text'    => '',
        'admin_subject' => '',
        'shortcode'     => 'comment,status,request_id,firstname,lastname,email,username,website_name,website_logo,name',
    );

    $this->db->insert("mail_templates", $data);
}


if($unique_id == 'order_on_vendor_program'){
    $admin_text = '<p>Dear Vendor,</p>
                    <p>New Order Created under your Program</p>
                    <p><b>Website</b> : [[external_website_name]]</p>
                    <p><b>Total</b> : [[total]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';

    $data = array(
        'unique_id' => $unique_id,
        'text'      => $admin_text,
        'subject'   => 'New Order Create In Your Program',
        'name'      => 'New Order in Vendor Program',
        'shortcode' => 'external_website_name,commission,username,website_name,website_logo,product_ids,total,currency,commission_type,script_name',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'vendor_create_ads'){
    $admin_text = '<p>Dear Admin,</p>
                    <p>New Ads - [[type]] has been created</p>
                    <p>Name [[name]]</p>
                    <p>Username [[username]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';

    $data = array(
        'unique_id'     => $unique_id,
        'admin_text'    => $admin_text,
        'name'          => 'Vendor Create Ads (Banner, Text, Link, Video)',
        'admin_subject' => 'New Ads ([[type]]) Created By Vendor',
        'shortcode' => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name,type,tool_type',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'vendor_ads_status_0'){
    $admin_text = '<p>Dear</p>
                    <p>Ads - [[type]] Status Change to In Review </p>
                    <p>Name [[name]]</p>
                    <p>Username [[username]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';

    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'admin_text'    => $admin_text,
        'name'          => 'Vendor Ads (Banner, Text, Link, Video) Status Change To In Review',
        'subject'       => 'Ads ([[type]]) Status Change To In Review',
        'admin_subject' => 'Ads ([[type]]) Status Change To In Review',
        'shortcode' => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name,type,tool_type',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'vendor_ads_status_1'){
    $admin_text = '<p>Dear</p>
                    <p>Ads - [[type]] Status Change to Approved </p>
                    <p>Name [[name]]</p>
                    <p>Username [[username]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';

    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'admin_text'    => $admin_text,
        'name'          => 'Vendor Ads (Banner, Text, Link, Video) Status Change To Approved',
        'subject'       => 'Ads ([[type]]) Status Change To Approved',
        'admin_subject' => 'Ads ([[type]]) Status Change To Approved',
        'shortcode' => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name,type,tool_type',
    );

    $this->db->insert("mail_templates", $data);
}


if($unique_id == 'vendor_ads_status_2'){
    $admin_text = '<p>Dear</p>
                    <p>Ads - [[type]] Status Change to Denied </p>
                    <p>Name [[name]]</p>
                    <p>Username [[username]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';

    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'admin_text'    => $admin_text,
        'name'          => 'Vendor Ads (Banner, Text, Link, Video) Status Change To Denied',
        'subject'       => 'Ads ([[type]]) Status Change To Denied',
        'admin_subject' => 'Ads ([[type]]) Status Change To Denied',
        'shortcode' => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name,type,tool_type',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'vendor_ads_status_3'){
    $admin_text = '<p>Dear</p>
                    <p>Ads - [[type]] Status Change to Ask To Edit</p>
                    <p>Name [[name]]</p>
                    <p>Username [[username]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';

    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'admin_text'    => $admin_text,
        'name'          => 'Vendor Ads (Banner, Text, Link, Video) Status Change To Ask To Edit',
        'subject'       => 'Ads ([[type]]) Status Change To Ask To Edit',
        'admin_subject' => 'Ads ([[type]]) Status Change To Ask To Edit',
        'shortcode' => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name,type,tool_type',
    );

    $this->db->insert("mail_templates", $data);
}


if($unique_id == 'vendor_create_program'){
    $admin_text = '<p>Dear Admin,</p>
                    <p>New Program has been created</p>
                    <p>Name [[name]]</p>
                    <p>Username [[username]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';
            
    $data = array(
        'unique_id'     => $unique_id,
        'admin_text'    => $admin_text,
        'name'          => 'Vendor Create new product',
        'admin_subject' => 'New Program Created By Vendor : [[name]]',
        'shortcode' => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'vendor_program_status_0'){
    $admin_text = '<p>Dear,</p>
                    <p>Program Status Change to In Review</p>
                    <p>Name [[name]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';
    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'admin_text'    => $admin_text,
        'name'          => 'Vendor Program Status Change To In Review',
        'subject'       => 'Program Status Change To In Review',
        'admin_subject' => 'Program Status Change To In Review',
        'shortcode'     => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'vendor_program_status_1'){
    $admin_text = '<p>Dear,</p>
                    <p>Program Status Change to Approved</p>
                    <p>Name [[name]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';
    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'admin_text'    => $admin_text,
        'name'          => 'Vendor Program Status Change To Approved',
        'subject'       => 'Program Status Change To Approved',
        'admin_subject' => 'Program Status Change To Approved',
        'shortcode'     => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'vendor_program_status_2'){
    $admin_text = '<p>Dear,</p>
                    <p>Program Status Change to Denied</p>
                    <p>Name [[name]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';
    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'admin_text'    => $admin_text,
        'name'          => 'Vendor Program Status Change To Denied',
        'subject'       => 'Program Status Change To Denied',
        'admin_subject' => 'Program Status Change To Denied',
        'shortcode'     => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'vendor_program_status_3'){
    $admin_text = '<p>Dear,</p>
                    <p>Program Status Change to Ask To Edit</p>
                    <p>Name [[name]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';
    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'admin_text'    => $admin_text,
        'name'          => 'Vendor Program Status Change To Ask To Edit',
        'subject'       => 'Program Status Change To Ask To Edit',
        'admin_subject' => 'Program Status Change To Ask To Edit',
        'shortcode'     => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,name',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'vendor_create_product'){
    $admin_text = '<p>Dear Admin,</p>
                    <p>New Product has been created</p>
                    <p>Name [[product_name]]</p>
                    <p>Username [[username]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';

    $data = array(
        'unique_id'     => $unique_id,
        'admin_text'    => $admin_text,
        'name'          => 'Vendor Create new product',
        'admin_subject' => 'New Product Created By Vendor',
        'shortcode' => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,product_name,product_short_description,product_price,product_sku,product_id',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'vendor_product_status_0'){
    $admin_text = '<p>Dear,</p>
                    <p>Product Status Change to In Review</p>
                    <p>Name [[product_name]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';
    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'admin_text'    => $admin_text,
        'name'          => 'Vendor Product Status Change To In Review',
        'subject'       => 'Product Status Change To In Review',
        'admin_subject' => 'Product Status Change To In Review',
        'shortcode'     => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,product_name,product_short_description,product_price,product_sku,product_id',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'vendor_product_status_1'){
    $admin_text = '<p>Dear, [[username]]</p>
                    <p>Product Status Change to Approved</p>
                    <p>Name [[product_name]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';
    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'name'          => 'Vendor Product Status Change To Approved',
        'subject'       => 'Product Status Change To Approved',
        'shortcode'     => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,product_name,product_short_description,product_price,product_sku,product_id',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'vendor_product_status_2'){
    $admin_text = '<p>Dear, [[username]]</p>
                    <p>Product Status Change to Denied</p>
                    <p>Name [[product_name]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';
    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'name'          => 'Vendor Product Status Change To Denied',
        'subject'       => 'Product Status Change To Denied',
        'shortcode'     => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,product_name,product_short_description,product_price,product_sku,product_id',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'vendor_product_status_3'){
    $admin_text = '<p>Dear, [[username]]</p>
                    <p>Product Status Change to Ask To Edit</p>
                    <p>Name [[product_name]]</p>
                    <p><br />
                [[website_name]]<br />
                Support Team</p>
            ';
    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'name'          => 'Vendor Product Status Change To Ask To Edit',
        'subject'       => 'Product Status Change To Ask To Edit',
        'shortcode'     => 'admin_last_message,vendor_last_message,firstname,lastname,email,username,website_name,website_logo,product_name,product_short_description,product_price,product_sku,product_id',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'vendor_order_status_complete'){
    $admin_text = '<p>Hello Vendor,</p>
        <p>you got new order Sale Commission from sale thats done under [[firstname]] [[lastname]]</p>
        <p>Commission: [[vendor_commission]] </p>
        <p><strong>Commission for product_name :&nbsp;</strong>[[product_name]]</p>
        [[website_name]]<br />
                Support Team</p>
    ';

    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'name'          => 'Vendor Order Status Has Been Change',
        'subject'       => 'Vendor: New Order Commission From [[firstname]] [[lastname]]',
        'shortcode'     => 'vendor_firstname,vendor_lastname,vendor_commission,order_id,status,order_link,product_name,commission_type,PhoneNumber,firstname,lastname,commission,total,currency_code,txn_id,website_name,website_logo,order_id',
    );

    $this->db->insert("mail_templates", $data);
}

if($unique_id == 'new_order_for_vendor'){
    $admin_text = '<p>Hello Vendor,</p>
        <p>you got new order from [[firstname]] [[lastname]]</p>
        <p>Commission: [[vendor_commission]] </p>
        <p>Order Status: [[status]] </p>
        <p><strong>Commission for product_name :&nbsp;</strong>[[product_name]]</p>
        [[website_name]]<br />
                Support Team</p>
    ';

    $data = array(
        'unique_id'     => $unique_id,
        'text'          => $admin_text,
        'name'          => 'Vendor Got New Order',
        'subject'       => 'Vendor: You have new order from [[firstname]] [[lastname]]',
        'shortcode'     => 'vendor_firstname,vendor_lastname,vendor_commission,order_id,status,order_link,product_name,PhoneNumber,firstname,lastname,commission,total,currency_code,txn_id,website_name,website_logo,order_id',
    );

    $this->db->insert("mail_templates", $data);
}

$template = $this->db->query("SELECT * FROM mail_templates WHERE unique_id = '". $unique_id ."'")->row_array();