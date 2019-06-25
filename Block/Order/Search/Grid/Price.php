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

namespace Mageplaza\CurrencyFormatter\Block\Order\Search\Grid;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\Price as PriceRenderer;
use Magento\Framework\DataObject;

/**
 * Class Price
 * @package Mageplaza\CurrencyFormatter\Block\Order\Search\Grid
 */
class Price extends PriceRenderer
{

    public function render(DataObject $row)
    {
        if ($row->getTypeId() == 'downloadable') {
            $row->setPrice($row->getPrice());
        }
        
        if ($data = $this->_getValue($row)) {
            $currencyCode = $this->_getCurrencyCode($row);
        
            if (!$currencyCode) {
                return $data;
            }
        
            $data = (float)$data * $this->_getRate($row);
            $data = sprintf("%f", $data);
            $data = $this->_localeCurrency->getCurrency($currencyCode)->toCurrency($data);
            return $data;
        }
        return $this->getColumn()->getDefault();
    }
}
