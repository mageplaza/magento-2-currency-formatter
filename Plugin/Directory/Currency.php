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

namespace Mageplaza\CurrencyFormatter\Plugin\Directory;

use Magento\Directory\Model\Currency as DirectoryCurrency;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\CurrencyFormatter\Helper\Data as HelperData;
use Mageplaza\CurrencyFormatter\Plugin\AbstractFormat;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\Locale\FormatInterface;
use Zend_Currency;

/**
 * Class Currency
 * @package Mageplaza\CurrencyFormatter\Plugin\Directory
 */
class Currency extends AbstractFormat
{
    /**
     * @var CurrencyInterface
     */
    protected $_localeCurrency;
    
    /**
     * @var FormatInterface
     */
    protected $_localeFormat;
    
    /**
     * Currency constructor.
     * @param StoreManagerInterface $storeManager
     * @param HelperData $helperData
     * @param ResolverInterface $localeResolver
     * @param CurrencyInterface $localeCurrency
     * @param FormatInterface $localeFormat
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        HelperData $helperData,
        ResolverInterface $localeResolver,
        CurrencyInterface $localeCurrency,
        FormatInterface $localeFormat
    ) {
        $this->_localeCurrency = $localeCurrency;
        $this->_localeFormat = $localeFormat;
        parent::__construct($storeManager, $helperData, $localeResolver);
    }
    
    /**
     * @param DirectoryCurrency $subject
     * @param callable $proceed
     * @param $price
     * @param array $options
     * @return string
     * @throws NoSuchEntityException
     * @throws \Zend_Currency_Exception
     */
    public function aroundFormatTxt(DirectoryCurrency $subject, callable $proceed, $price, $options = [])
    {
        if (!$this->_helperData->isEnabled()) {
            return $proceed($price, $options = []);
        }
        
        $currency= $this->getCurrencyCode();
        $locale = $this->getLocaleCode();
        $original = $this->_helperData->getOriginalFormat($locale, $currency);
        $config = $this->getFormatByCurrency($currency);
        $decimal = (int) $config['decimal_number'];
        
        if (!is_numeric($price)) {
            $price = $this->_localeFormat->getNumber($price);
        }
        
        $price = sprintf('%F', $price);
        $options['precision'] = $decimal;
        $options['display'] = Zend_Currency::NO_SYMBOL;
        
        $firstResult = $this->_localeCurrency->getCurrency($currency)->toCurrency($price, $options);
        $finalResult = $this->_helperData->getDirectoryCurrency($firstResult, $decimal, $original, $config);
        
        return $finalResult;
    }
}
