<?php

namespace AffiliatePro\Magento2\Block\Product\View;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Senddata
 * @package AffiliatePro\Magento2\Block\Product\View
 */
class Senddata extends Template
{
    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * Magento2 constructor.
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_registry = $registry;
    }

    /**
     * @return mixed
     */
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }
}
