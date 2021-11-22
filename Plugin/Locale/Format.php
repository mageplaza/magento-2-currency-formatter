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

namespace Mageplaza\CurrencyFormatter\Plugin\Locale;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Locale\Format as LocaleFormat;
use Mageplaza\CurrencyFormatter\Plugin\AbstractFormat;

/**
 * Class Format
 * @package Mageplaza\CurrencyFormatter\Plugin\Locale
 */
class Format extends AbstractFormat
{
    /**
     * @param LocaleFormat $subject
     * @param callable $proceed
     * @param string $localeCode Locale code.
     * @param string $currencyCode Currency code.
     *
     * @return array
     */
    public function aroundGetPriceFormat(
        LocaleFormat $subject,
        callable $proceed,
        $localeCode = null,
        $currencyCode = null
    ) {
        $result = $proceed();

        if (!$this->_helperData->isEnabled()) {
            return $result;
        }
        $result['currencyCode'] = $currencyCode;

        return $result;
    }

    /**
     * @param LocaleFormat $subject
     * @param $result
     *
     * @return mixed
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function afterGetPriceFormat(LocaleFormat $subject, $result)
    {
        if (!$this->_helperData->isEnabled()) {
            return $result;
        }
        $baseCurrencyCode = $this->_storeManager->getStore()->getBaseCurrency()->getCode();
        $code             = $this->getCurrencyCode();
        if ($baseCurrencyCode && isset($result['currencyCode']) && $result['currencyCode'] === $baseCurrencyCode) {
            $code = $baseCurrencyCode;
        }

        $config                      = $this->getFormatByCurrency($code);
        $localeShowSymbol            = $this->_helperData->getLocaleShowSymbol(
            $code,
            $config['show_symbol'],
            $config['symbol']
        );
        $result['pattern']           = $localeShowSymbol;
        $result['precision']         = $config['decimal_number'];
        $result['requiredPrecision'] = $config['decimal_number'];
        $result['decimalSymbol']     = $config['decimal_separator'];
        $result['groupSymbol']       = $config['group_separator'];
        $result['showMinus']         = $config['show_minus'];
        $result['symbol']            = $config['symbol'];
        $result['minusSign']         = $config['minus_sign'];

        return $result;
    }
}
