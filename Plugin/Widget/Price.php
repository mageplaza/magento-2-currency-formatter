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

use Magento\Backend\Block\Widget\Grid\Column\Renderer\Price as WidgetPrice;
use Magento\Framework\DataObject;
use Mageplaza\CurrencyFormatter\Plugin\AbstractFormat;

/**
 * Class Price
 * @package Mageplaza\CurrencyFormatter\Plugin\Widget
 */
class Price extends AbstractFormat
{
    /**
     * @param WidgetPrice $subject
     * @param callable $proceed
     * @param DataObject $row
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Zend_Currency_Exception
     */
    public function aroundRender(WidgetPrice $subject, callable $proceed, DataObject $row)
    {
        $storeId = $this->_helperData->isAdmin() ? 0 : $this->_storeManager->getStore()->getId();
        if (!$this->_helperData->isEnabled($storeId)) {
            return $proceed($row);
        }
        
        if ($data = $this->getSubjectValue($subject, $row)) {
            $currencyCode = $this->getSubjectCurrencyCode($subject, $row);
            if (!$currencyCode) {
                return $data;
            }
            
            $data = (float)$data * $this->getSubjectRate($subject, $row);
            $data = sprintf('%f', $data);
            $data = $this->formatCurrencyText($currencyCode, $data, $storeId);
            return $data;
        }
        return $subject->getColumn()->getDefault();
    }
}
