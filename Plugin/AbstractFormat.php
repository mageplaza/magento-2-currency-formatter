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

namespace Mageplaza\CurrencyFormatter\Plugin;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Mageplaza\CurrencyFormatter\Helper\Data as HelperData;
use Magento\Framework\Locale\ResolverInterface;

/**
 * Class AbstractFormat
 * @package Mageplaza\CurrencyFormatter\Plugin
 */
abstract class AbstractFormat
{
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    
    /**
     * @var HelperData
     */
    protected $_helperData;
    
    /**
     * @var ResolverInterface
     */
    protected $_localeResolver;
    
    /**
     * AbstractFormat constructor.
     * @param StoreManagerInterface $storeManager
     * @param HelperData $helperData
     * @param ResolverInterface $localeResolver
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        HelperData $helperData,
        ResolverInterface $localeResolver
    ) {
        $this->_helperData = $helperData;
        $this->_storeManager = $storeManager;
        $this->_localeResolver = $localeResolver;
    }
    
    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
    
    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getCurrencyCode()
    {
        return $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
    }
    
    /**
     * @param $currencyCode
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getFormatByCurrency($currencyCode)
    {
        $storeId = $this->getStoreId();
        
        return $this->_helperData->getCurrencyConfig($currencyCode, $storeId);
    }
    
    /**
     * @return string
     */
    public function getLocaleCode()
    {
        return $this->_localeResolver->getLocale();
    }
}
