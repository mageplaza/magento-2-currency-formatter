<?php


namespace Mageplaza\CurrencyFormatter\Controller\Adminhtml\Test;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Backend\App\Action\Context;

class Index extends Action
{
    protected $_storeManager;
    protected $_currencyInterface;
    protected $_localeFormat;
    protected $_localeResolver;

    public function __construct (
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Locale\CurrencyInterface $currencyInterface,
        \Magento\Framework\Locale\Format $localeFormat,
        \Magento\Framework\Locale\Resolver $localeResolver
    )
    {
        $this->_storeManager = $storeManager;
        $this->_currencyInterface = $currencyInterface;
        $this->_localeFormat = $localeFormat;
        $this->_localeResolver = $localeResolver;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $currencyLocale = $this->_localeResolver->getLocale();
        
        $format = $this->_localeFormat->getPriceFormat($currencyLocale, 'USD');
        var_dump($format);
        die;
    }
}