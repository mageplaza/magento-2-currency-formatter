<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_CurrencyFormatter
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\CurrencyFormatter\Model\System\Config\Backend;

use Magento\Framework\App\Config\Value;
use Mageplaza\CurrencyFormatter\Helper\Data as HelperData;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Currencies
 * @package Mageplaza\CurrencyFormatter\Model\System\Config\Backend
 */
class Currencies extends Value
{
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    
    /**
     * @var RequestInterface
     */
    protected $_request;
    
    /**
     * @var HelperData
     */
    protected $_helperData;
    
    /**
     * Currencies constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param StoreManagerInterface $storeManager
     * @param RequestInterface $request
     * @param HelperData $helperData
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        StoreManagerInterface $storeManager,
        RequestInterface $request,
        HelperData $helperData,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_request = $request;
        $this->_helperData = $helperData;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }
    
    /**
     * @return Value
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        
        /** @var array $value */
        foreach ($value as $key => $saveConfig) {
            isset($saveConfig['use_default'])
                ? $value[$key] = $this->_helperData->getCurrencyDefaultConfig($key)
                : $value[$key]['use_default'] = '0';
        }
        
        $this->setValue($value);
        if (is_array($value)) {
            $this->setValue(HelperData::jsonEncode($value));
        }
        
        return parent::beforeSave();
    }
    
    /**
     * @return Value
     * @throws NoSuchEntityException
     */
    protected function _afterLoad()
    {
        $configValue = $this->getValue();
        $storeId = $this->_request->getParam('store', 0);
        $currencyCodes = $this->_helperData->getCurrenciesByStore($storeId);
        
        if (!is_array($configValue)) {
            $configValue = HelperData::jsonDecode($this->getValue());
            foreach ($currencyCodes as $code) {
                if (!isset($configValue[$code])) {
                    $configValue[$code] = $this->_helperData->getCurrencyDefaultConfig($code);
                }
            }
            
            /** @var array $configValue */
            foreach ($configValue as $key => $config) {
                if (!in_array($key, $currencyCodes, true)) {
                    unset($configValue[$key]);
                }
            }
        }

        $this->setValue($configValue);
        return parent::_afterLoad();
    }
}
