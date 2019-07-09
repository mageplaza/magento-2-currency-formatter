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
 * Class ShowSymbol
 * @package Mageplaza\CurrencyFormatter\Model\System\Config\Source
 */
class ShowSymbol extends OptionArray
{
    const BEFORE            = 'before';
    const BEFORE_WITH_SPACE = 'before_with_space';
    const AFTER             = 'after';
    const AFTER_WITH_SPACE  = 'after_with_space';
    const NONE              = 'none';

    /**
     * @return array
     */
    public function getOptionHash()
    {
        return [
            self::BEFORE            => __('Before value'),
            self::BEFORE_WITH_SPACE => __('Before value with space'),
            self::AFTER             => __('After value'),
            self::AFTER_WITH_SPACE  => __('After value with space'),
            self::NONE              => __('None'),
        ];
    }
}
