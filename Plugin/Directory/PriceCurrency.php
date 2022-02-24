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

use Magento\Directory\Model\PriceCurrency as DirectoryPriceCurrency;
use Magento\Framework\Exception\NoSuchEntityException;
use Mageplaza\CurrencyFormatter\Plugin\AbstractFormat;
use Zend_Currency_Exception;

/**
 * Class PriceCurrency
 * @package Mageplaza\CurrencyFormatter\Plugin\Directory
 */
class PriceCurrency extends AbstractFormat
{
    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getPrecision(): int
    {
        $baseCurrencyCode = $this->_storeManager->getStore()->getBaseCurrency()->getCode();
        $code             = $this->getCurrencyCode();
        if ($baseCurrencyCode && isset($result['currencyCode']) && $result['currencyCode'] === $baseCurrencyCode) {
            $code = $baseCurrencyCode;
        }

        $config = $this->getFormatByCurrency($code);

        return (int) $config['decimal_number'];
    }

    /**
     * @param DirectoryPriceCurrency $subject
     * @param callable $proceed
     * @param float $price
     *
     * @return float
     * @throws NoSuchEntityException
     * @throws Zend_Currency_Exception
     */
    public function aroundRound(DirectoryPriceCurrency $subject, callable $proceed, float $price): float
    {
        if (!$this->_helperData->isEnabled()) {
            return $proceed($price);
        }

        return round($price, $this->getPrecision());
    }

    /**
     * @param DirectoryPriceCurrency $subject
     * @param callable $proceed
     * @param float $price
     * @param int $precision
     *
     * @return float
     * @throws NoSuchEntityException
     * @throws Zend_Currency_Exception
     */
    public function aroundRoundPrice(
        DirectoryPriceCurrency $subject,
        callable $proceed,
        float $price,
        int $precision
    ): float {
        if (!$this->_helperData->isEnabled()) {
            return $proceed($price, $precision);
        }

        return round($price, $this->getPrecision());
    }
}
