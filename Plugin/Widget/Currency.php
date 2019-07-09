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

namespace Mageplaza\CurrencyFormatter\Plugin\Widget;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\Currency as WidgetCurrency;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Mageplaza\CurrencyFormatter\Plugin\AbstractFormat;
use Zend_Currency_Exception;

/**
 * Class Currency
 * @package Mageplaza\CurrencyFormatter\Plugin\Widget
 */
class Currency extends AbstractFormat
{
    /**
     * @param WidgetCurrency $subject
     * @param callable $proceed
     * @param DataObject $row
     *
     * @return string
     * @throws NoSuchEntityException
     * @throws Zend_Currency_Exception
     */
    public function aroundRender(WidgetCurrency $subject, callable $proceed, DataObject $row)
    {
        $storeId = $this->_helperData->isAdmin() ? 0 : $this->_storeManager->getStore()->getId();
        if (!$this->_helperData->isEnabled($storeId)) {
            return $proceed($row);
        }

        if ($data = $this->getSubjectValue($subject, $row)) {
            $currency_code = $this->getSubjectCurrencyCode($subject, $row);
            $data = (float) $data * $this->getSubjectRate($subject, $row);
            $sign = (bool) (int) $subject->getColumn()->getShowNumberSign() && $data > 0 ? '+' : '';
            $data = sprintf('%f', $data);
            $data = $this->formatCurrencyText($currency_code, $data, $storeId);

            return $sign . $data;
        }

        return $subject->getColumn()->getDefault();
    }
}
