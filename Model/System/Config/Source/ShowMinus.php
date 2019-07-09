<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category  Mageplaza
 * @package   Mageplaza_CurrencyFormatter
 * @copyright Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license   https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\CurrencyFormatter\Model\System\Config\Source;

/**
 * Class ShowMinus
 * @package Mageplaza\CurrencyFormatter\Model\System\Config\Source
 */
class ShowMinus extends OptionArray
{
    const BEFORE_VALUE  = 'before_value';
    const AFTER_VALUE   = 'after_value';
    const BEFORE_SYMBOL = 'before_symbol';
    const AFTER_SYMBOL  = 'after_symbol';

    /**
     * @return array
     */
    public function getOptionHash()
    {
        return [
            self::BEFORE_VALUE  => __('Before Value'),
            self::AFTER_VALUE   => __('After Value'),
            self::BEFORE_SYMBOL => __('Before Symbol'),
            self::AFTER_SYMBOL  => __('After Symbol'),
        ];
    }
}
