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

namespace Mageplaza\CurrencyFormatter\Block\Widget\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\Price as PriceRenderer;
use Magento\Framework\DataObject;

/**
 * Class Price
 * @package Mageplaza\CurrencyFormatter\Block\Widget\Renderer
 */
class Price extends PriceRenderer
{
    public function getValue(DataObject $row) {
        return $this->_getValue($row);
    }

    public function getCurrencyCode(DataObject $row) {
        return $this->_getCurrencyCode($row);
    }

    public function getRate(DataObject $row) {
        return $this->_getRate($row);
    }
}