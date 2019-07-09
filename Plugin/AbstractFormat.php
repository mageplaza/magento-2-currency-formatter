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

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Directory\Model\Currency\DefaultLocator;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\CurrencyFormatter\Helper\Data as HelperData;
use Mageplaza\CurrencyFormatter\Model\Locale\DefaultFormat;
use Zend_Currency;
use Zend_Currency_Exception;

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
     * @var CurrencyInterface
     */
    protected $_localeCurrency;

    /**
     * @var FormatInterface
     */
    protected $_localeFormat;

    /**
     * @var DefaultFormat
     */
    protected $_defaultFormat;

    /**
     * @var DefaultLocator
     */
    protected $_currencyLocator;

    /**
     * Request
     *
     * @var RequestInterface
     */
    protected $_request;

    /**
     * AbstractFormat constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param HelperData $helperData
     * @param ResolverInterface $localeResolver
     * @param CurrencyInterface $localeCurrency
     * @param FormatInterface $localeFormat
     * @param DefaultFormat $defaultFormat
     * @param DefaultLocator $currencyLocator
     * @param RequestInterface $request
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        HelperData $helperData,
        ResolverInterface $localeResolver,
        CurrencyInterface $localeCurrency,
        FormatInterface $localeFormat,
        DefaultFormat $defaultFormat,
        DefaultLocator $currencyLocator,
        RequestInterface $request
    ) {
        $this->_helperData = $helperData;
        $this->_storeManager = $storeManager;
        $this->_localeResolver = $localeResolver;
        $this->_localeCurrency = $localeCurrency;
        $this->_localeFormat = $localeFormat;
        $this->_defaultFormat = $defaultFormat;
        $this->_currencyLocator = $currencyLocator;
        $this->_request = $request;
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
     * @param string $currencyCode
     * @param null $storeId
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getFormatByCurrency($currencyCode, $storeId = null)
    {
        $storeId = ($storeId === null) ? $this->getStoreId() : $storeId;

        return $this->_helperData->getCurrencyConfig($currencyCode, $storeId);
    }

    /**
     * @return string
     */
    public function getLocaleCode()
    {
        return $this->_localeResolver->getLocale();
    }

    /**
     * @param string $currency
     * @param mixed $price
     * @param null $storeId
     *
     * @return mixed|string
     * @throws NoSuchEntityException
     * @throws Zend_Currency_Exception
     */
    public function formatCurrencyText($currency, $price, $storeId = null)
    {
        $locale = $this->getLocaleCode();
        $original = $this->_defaultFormat->getFormat($locale, $currency);
        $config = $this->getFormatByCurrency($currency, $storeId);
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

    /**
     * @param AbstractRenderer $subject
     * @param DataObject $row
     *
     * @return mixed|string
     */
    public function getSubjectValue($subject, $row)
    {
        if ($getter = $subject->getColumn()->getGetter()) {
            if (is_string($getter)) {
                return $row->{$getter}();
            }
            if (is_callable($getter)) {
                return call_user_func($getter, $row);
            }

            return '';
        }

        return $row->getData($subject->getColumn()->getIndex());
    }

    /**
     * @param AbstractRenderer $subject
     * @param DataObject $row
     *
     * @return mixed
     */
    public function getSubjectCurrencyCode($subject, $row)
    {
        if ($code = $subject->getColumn()->getCurrencyCode()) {
            return $code;
        }
        if ($code = $row->getData($subject->getColumn()->getCurrency())) {
            return $code;
        }

        return $this->_currencyLocator->getDefaultCurrency($this->_request);
    }

    /**
     * @param AbstractRenderer $subject
     * @param DataObject $row
     *
     * @return float
     * @throws NoSuchEntityException
     */
    public function getSubjectRate($subject, $row)
    {
        if ($rate = $subject->getColumn()->getRate()) {
            return (float) $rate;
        }
        if ($rate = $row->getData($subject->getColumn()->getRateField())) {
            return (float) $rate;
        }

        $currencyCode = $this->getSubjectCurrencyCode($subject, $row);

        return $this->_storeManager->getStore()->getBaseCurrency()->getRate($currencyCode);
    }
}
