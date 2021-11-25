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
define([
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils'
], function ($, quote, priceUtils) {
    'use strict';

    var content   = '%s',
        newFormat = {
            decimalSymbol: '',
            groupLength: '',
            groupSymbol: '',
            integerRequired: '',
            pattern: '',
            precision: '',
            requiredPrecision: ''
        };

    var mixins = {
        getFormattedPrice: function (price) {
            var format     = quote.getPriceFormat(),
                newPattern = null,
                newPrice   = null;

            if (price >= 0) {
                return this._super(price);
            }

            if (format.showMinus && format.minusSign && format.symbol) {
                newPrice = price * -1;
                switch (format.showMinus){
                    case 'before_value':
                        newPattern = format.pattern.replace(content, format.minusSign + content);
                        break;
                    case 'after_value':
                        newPattern = format.pattern.replace(content, content + format.minusSign);
                        break;
                    case 'before_symbol':
                        newPattern = format.pattern.replace(format.symbol, format.minusSign + format.symbol);
                        break;
                    case 'after_symbol':
                        newPattern = format.pattern.replace(format.symbol, format.symbol + format.minusSign);
                        break;
                    default:
                        newPattern = format.pattern;
                        break;
                }

                if (format.pattern.indexOf(format.symbol) === -1) {
                    newPattern = format.minusSign + format.pattern;
                }

                $.each(newFormat, function (key) {
                    if (key === 'pattern') {
                        newFormat['pattern'] = newPattern;
                    } else {
                        newFormat[key] = format[key];
                    }
                });

                return priceUtils.formatPrice(newPrice, newFormat);
            }

            return this._super(price);
        }
    };

    return function (AbstractTotal) {
        return AbstractTotal.extend(mixins);
    };
});

