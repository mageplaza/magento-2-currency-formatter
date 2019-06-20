<?php


namespace Mageplaza\CurrencyFormatter\Controller\Test;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Mageplaza\CurrencyFormatter\Helper\Data as HelperData;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Registry;

/**
 * Class Index
 * @package Mageplaza\CurrencyFormatter\Controller\Test
 */
class Index extends Action
{
    protected $_helperData;
    
    protected $_storeManager;
    
    protected $_localeFormat;
    
    protected $_registry;
    
    protected $_defaultFormat;
    
    public function __construct(
        Context $context,
        HelperData $helperData,
        StoreManagerInterface $storeManager,
        FormatInterface $localeFormat,
        Registry $registry,
        \Mageplaza\CurrencyFormatter\Model\Locale\DefaultFormat $defaultFormat
    ) {
        $this->_helperData = $helperData;
        $this->_localeFormat = $localeFormat;
        $this->_storeManager = $storeManager;
        $this->_registry = $registry;
        $this->_defaultFormat = $defaultFormat;
        
        parent::__construct($context);
    }
    
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        \Zend_Debug::dump($this->_defaultFormat->getFormat('en_US', 'GBP'));
        \Zend_Debug::dump($this->_defaultFormat->getPriceFormat('en_US', 'GBP'));
    }
}
