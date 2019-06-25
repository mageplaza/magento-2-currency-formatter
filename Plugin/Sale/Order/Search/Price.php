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

namespace Mageplaza\CurrencyFormatter\Plugin\Sale\Order\Search;

use Magento\Sales\Block\Adminhtml\Order\Create\Search\Grid\Renderer\Price as PriceRenderer;
use Mageplaza\CurrencyFormatter\Plugin\AbstractFormat;
use Magento\Framework\DataObject;

/**
 * Class Price
 * @package Mageplaza\CurrencyFormatter\Plugin\Sale\Order\Search
 */
class Price extends AbstractFormat
{
    public function aroundRender(PriceRenderer $subject, callable $proceed, DataObject $row)
    {
        
    }
}