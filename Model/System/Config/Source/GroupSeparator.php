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
 * Class GroupSeparator
 * @package Mageplaza\CurrencyFormatter\Model\System\Config\Source
 */
class GroupSeparator extends OptionArray
{
    /**
     * @return array
     */
    public function getOptionHash()
    {
        return [
            self::COMMA => __("Comma (,)"),
            self::TICK  => __("Tick (')"),
            self::DOT   => __("Dot (.)"),
            self::SPACE => __("Space ( )"),
            self::NONE  => __("None"),
        ];
    }
}
