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
use Magento\Store\Model\ScopeInterface;

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
     * @throws NoSuchEntityException
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        $scope = $this->getScope();

        /** @var array $value */
        foreach ($value as $key => $saveConfig) {
            if (isset($saveConfig['use_default'])) {
                switch ($scope) {
                    case ScopeInterface::SCOPE_WEBSITES:
                        $value[$key] = $this->_helperData->getCurrencyConfig($key);
                        $value[$key]['use_default'] = 1;
                        break;
                    case ScopeInterface::SCOPE_STORES:
                        $value[$key] = $this->_helperData->getCurrencyWebsiteConfig($key, $this->getScopeId());
                        $value[$key]['use_default'] = 1;
                        break;
                    default:
                        unset($value[$key]);
                }
            }
        }

        if (is_array($value)) {
            $this->setValue(HelperData::jsonEncode($value));
        }
        
        return parent::beforeSave();
    }
}
