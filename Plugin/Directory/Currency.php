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
use Magento\Framework\Exception\NoSuchEntityException;
use Mageplaza\CurrencyFormatter\Plugin\AbstractFormat;
use Zend_Currency_Exception;

/**
 * Class Currency
 * @package Mageplaza\CurrencyFormatter\Plugin\Directory
 */
class Currency extends AbstractFormat
{
    /**
     * @param DirectoryCurrency $subject
     * @param callable $proceed
     * @param $price
     * @param array $options
     *
     * @return string
     * @throws NoSuchEntityException
     * @throws Zend_Currency_Exception
     */
    public function aroundFormatTxt(DirectoryCurrency $subject, callable $proceed, $price, $options = [])
    {
        if (!$this->_helperData->isEnabled()) {
            return $proceed($price, $options);
        }
        $storeId = $this->_helperData->isAdmin() ? 0 : null;
        $currency = $subject->getCurrencyCode();

        return $this->formatCurrencyText($currency, $price, $storeId);
    }
}
