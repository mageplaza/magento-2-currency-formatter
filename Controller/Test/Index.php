<?php


namespace Mageplaza\CurrencyFormatter\Controller\Test;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Mageplaza\CurrencyFormatter\Helper\Data as HelperData;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Locale\FormatInterface;;
use Magento\Framework\Registry;

class Index extends Action
{
    protected $_helperData;
    
    protected $_storeManager;
    
    protected $_localeFormat;
    
    protected $_registry;
    
    public function __construct(
        Context $context,
        HelperData $helperData,
        StoreManagerInterface $storeManager,
        FormatInterface $localeFormat,
        Registry $registry
    )
    {
        $this->_helperData = $helperData;
        $this->_localeFormat = $localeFormat;
        $this->_storeManager = $storeManager;
        $this->_registry = $registry;
        
        parent::__construct($context);
    }
    
    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        //$storeId = $this->_storeManager->getStore()->getId();
        \Zend_Debug::dump($this->_localeFormat->getPriceFormat());
        
        \Zend_Debug::dump($this->_registry->registry('mpcf_origin'));
        
        
        
//        die();
//        $this->_helperData->getFormat($storeId);
    }
}