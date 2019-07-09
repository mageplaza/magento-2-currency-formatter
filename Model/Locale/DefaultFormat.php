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

namespace Mageplaza\CurrencyFormatter\Model\Locale;

use NumberFormatter;

/**
 * Class DefaultFormat
 * @package Mageplaza\CurrencyFormatter\Model\Locale
 */
class DefaultFormat
{
    const CONTENT = '%s';

    /**
     * @param string $localeCode
     * @param string $currencyCode
     *
     * @return array
     */
    public function getFormat($localeCode, $currencyCode)
    {
        $formatter = new NumberFormatter(
            $localeCode . '@currency=' . $currencyCode,
            NumberFormatter::CURRENCY
        );

        $format = $formatter->getPattern();
        $decimalSymbol = $formatter->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
        $groupSymbol = $formatter->getSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL);

        $pos = strpos($format, ';');
        if ($pos !== false) {
            $format = substr($format, 0, $pos);
        }
        $format = preg_replace("/[^0\#\.,]/", '', $format);
        $totalPrecision = 0;
        $decimalPoint = strpos($format, '.');
        if ($decimalPoint !== false) {
            $totalPrecision = strlen($format) - (strrpos($format, '.') + 1);
        } else {
            $decimalPoint = strlen($format);
        }
        $requiredPrecision = $totalPrecision;
        $t = substr($format, $decimalPoint);
        $pos = strpos($t, '#');
        if ($pos !== false) {
            $requiredPrecision = strlen($t) - $pos - $totalPrecision;
        }

        $result = [
            'requiredPrecision' => $requiredPrecision,
            'decimalSymbol'     => $decimalSymbol,
            'groupSymbol'       => $groupSymbol,
        ];

        return $result;
    }
}
