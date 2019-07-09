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

namespace Mageplaza\CurrencyFormatter\Plugin\Sale;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\Order as SaleOrder;
use Mageplaza\CurrencyFormatter\Plugin\AbstractFormat;
use Zend_Currency_Exception;

/**
 * Class Order
 * @package Mageplaza\CurrencyFormatter\Plugin\Sale
 */
class Order extends AbstractFormat
{
    /**
     * @param SaleOrder $subject
     * @param callable $proceed
     * @param $price
     * @param $precision
     * @param bool $addBrackets
     *
     * @return mixed
     * @throws NoSuchEntityException
     * @throws Zend_Currency_Exception
     */
    public function aroundFormatPricePrecision(
        SaleOrder $subject,
        callable $proceed,
        $price,
        $precision,
        $addBrackets = false
    ) {
        if (!$this->_helperData->isEnabled($subject->getStoreId())) {
            return $proceed($price, $precision, $addBrackets);
        }

        $currency = $subject->getOrderCurrencyCode();

        return $this->formatCurrencyText($currency, $price, $subject->getStoreId());
    }
}
