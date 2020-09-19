<?php
/**
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Ps_aff extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'ps_aff';
        $this->tab = 'advertising_marketing';
        $this->version = '1.0.0';
        $this->author = 'Affiliate Pro';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Affiliate Pro');
        $this->description = $this->l('Track click and sale');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('PS_AFF_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('DisplayHeader') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayOrderConfirmation') &&
            $this->registerHook('displayProductButtons');
    }

    public function uninstall()
    {
        Configuration::deleteByName('PS_AFF_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitPS_AFFModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPS_AFFModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array());
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array();
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'PS_AFF_LIVE_MODE' => Configuration::get('PS_AFF_LIVE_MODE', true),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookDisplayHeader(array $params)
    {
        $this->context->controller->addJS('__baseurl__integration/script');
        $this->context->controller->registerJavascript(
           'ps_aff',
           '__baseurl__integration/script',
           ['server' => 'remote', 'position' => 'head', 'priority' => 20]
        );
    }

    public function hookDisplayOrderConfirmation($order)
    {
        global $currency;
        $my_currency_iso_code = $currency->iso_code;

        if(isset($order['order'])){
            $orderObj = $order['order'];
            $total = $orderObj->total_paid;
        }
        if(isset($order['objOrder'])){
            $orderObj = $order['objOrder'];
            $total = $orderObj['total_to_pay'];
        }

        if(isset($orderObj)){
            global $smarty;
            $context = Context::getContext();

            $base_url = _PS_BASE_URL_.__PS_BASE_URI__; 
            $orderInfo = $orderObj;

            $ipaddress = "";
            if (getenv("HTTP_CLIENT_IP")) $ipaddress = getenv("HTTP_CLIENT_IP");
            else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");
            else if(getenv("HTTP_X_FORWARDED")) $ipaddress = getenv("HTTP_X_FORWARDED");
            else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress = getenv("HTTP_FORWARDED_FOR");
            else if(getenv("HTTP_FORWARDED")) $ipaddress = getenv("HTTP_FORWARDED");
            else if(getenv("REMOTE_ADDR")) $ipaddress = getenv("REMOTE_ADDR");
            else $ipaddress = "UNKNOWN";

            $affliate_cookie = (Tools::getIsset('af_id') ? Tools::getValue('af_id') : (isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : '') );
             
            $affiliateData = array(
                "order_id"       => $orderInfo->id,
                "order_currency" => $my_currency_iso_code,
                "order_total"    => $total,
                "product_ids"    => array(),
                "af_id"          => $affliate_cookie,
                "ip"             => $ipaddress,
                "base_url"       => base64_encode($base_url),
                "script_name"    => "prestashop",
            );

            $products = $orderInfo->getProducts();            
            foreach ($products as $_product) {
                $affiliateData["product_ids"][] = $_product['product_id'];
            }
           
            $context_options = stream_context_create(array(
                'http'=>array(
                    'method'=>"GET",
                    'header'=> "User-Agent: ". (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '') ."\r\n"."Referer: \r\n"
                )
            ));

            Tools::file_get_contents("__baseurl__integration/addOrder?".http_build_query($affiliateData), false, $context_options);
        }
    }

    public function hookDisplayProductButtons($product_info){
        
        if (isset($product_info['product'])) {
        
            global $smarty;
            $context = Context::getContext();
            $p = (array)$product_info['product'];
            $base_url = _PS_BASE_URL_.__PS_BASE_URI__; 
            $product_id = $product_info['product']['id'];
            $current_url = $context->link->getProductLink($product_id);

            $ipaddress = "";
            if (getenv("HTTP_CLIENT_IP")) $ipaddress = getenv("HTTP_CLIENT_IP");
            else if(getenv("HTTP_X_FORWARDED_FOR")) $ipaddress = getenv("HTTP_X_FORWARDED_FOR");
            else if(getenv("HTTP_X_FORWARDED")) $ipaddress = getenv("HTTP_X_FORWARDED");
            else if(getenv("HTTP_FORWARDED_FOR")) $ipaddress = getenv("HTTP_FORWARDED_FOR");
            else if(getenv("HTTP_FORWARDED")) $ipaddress = getenv("HTTP_FORWARDED");
            else if(getenv("REMOTE_ADDR")) $ipaddress = getenv("REMOTE_ADDR");
            else $ipaddress = "UNKNOWN";

            $affliate_cookie = (Tools::getIsset('af_id') ? Tools::getValue('af_id') : (isset($_COOKIE["af_id"]) ? $_COOKIE["af_id"] : '') );  
            
            $affiliateData = array(
                "product_id"       => $product_id,
                "af_id"            => $affliate_cookie,
                "ip"               => $ipaddress,
                "base_url"         => base64_encode($base_url),
                "script_name"      => "prestashop",
                "current_page_url" => base64_encode($current_url)
            );

            $context_options = stream_context_create(array(
                'http'=>array(
                    'method'=>"GET",
                    'header'=> "User-Agent: ". (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '') ."\r\n"."Referer: ". $current_url ."\r\n"
                )
            ));

            Tools::file_get_contents("__baseurl__integration/addClick?".http_build_query($affiliateData), false, $context_options);
        }
    }
}
