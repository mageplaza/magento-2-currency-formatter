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

namespace Mageplaza\CurrencyFormatter\Model\System\Config\Backend;

use Magento\Framework\App\Config\Value;
use Mageplaza\CurrencyFormatter\Helper\Data as HelperData;

/**
 * Class Currencies
 * @package Mageplaza\CurrencyFormatter\Model\System\Config\Backend
 */
class Currencies extends Value
{
    /**
     * @return Value
     */
    public function beforeSave()
    {
        $value = $this->getValue();

        /** @var array $value */
        foreach ($value as $key => $saveConfig) {
            if (isset($saveConfig['use_default'])) {
                unset($value[$key]);
            }
        }

        if (is_array($value)) {
            $this->setValue(HelperData::jsonEncode($value));
        }

        return parent::beforeSave();
    }
}
