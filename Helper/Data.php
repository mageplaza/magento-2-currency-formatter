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

namespace Mageplaza\CurrencyFormatter\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Locale\Resolver as LocaleResolver;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Core\Helper\AbstractData;
use Mageplaza\CurrencyFormatter\Model\System\Config\Source\DecimalNumber;
use Mageplaza\CurrencyFormatter\Model\System\Config\Source\DecimalSeparator;
use Mageplaza\CurrencyFormatter\Model\System\Config\Source\GroupSeparator;
use Mageplaza\CurrencyFormatter\Model\System\Config\Source\ShowSymbol;
use Mageplaza\CurrencyFormatter\Model\System\Config\Source\ShowMinus;
use Magento\Framework\Locale\CurrencyInterface;
use Mageplaza\CurrencyFormatter\Model\Locale\DefaultFormat;
use Magento\Directory\Model\CurrencyFactory;
use Magento\CurrencySymbol\Model\System\Currencysymbol;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Mageplaza\CurrencyFormatter\Helper
 */
class Data extends AbstractData
{
    const CONFIG_MODULE_PATH = 'mpcurrencyformatter';
    const MINUS_SIGN = '-';
    const CURRENCY_WEBSITE = 'currency/options/allow';

    /**
     * @var DecimalNumber
     */
    protected $_decimalNumber;
    
    /**
     * @var DecimalSeparator
     */
    protected $_decimalSeparator;
    
    /**
     * @var GroupSeparator
     */
    protected $_groupSeparator;
    
    /**
     * @var ShowSymbol
     */
    protected $_showSymbol;
    
    /**
     * @var ShowMinus
     */
    protected $_showMinus;
    
    /**
     * @var CurrencyInterface
     */
    protected $_localeCurrency;
    
    /**
     * @var DefaultFormat
     */
    protected $_defaultFormat;
    
    /**
     * @var LocaleResolver
     */
    protected $_localeResolver;
    
    /**
     * @var CurrencyFactory
     */
    protected $_currencyFactory;

    /**
     * @var Currencysymbol
     */
    protected $_currencySymbol;
    
    /**
     * Data constructor.
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param DecimalNumber $decimalNumber
     * @param DecimalSeparator $decimalSeparator
     * @param GroupSeparator $groupSeparator
     * @param ShowSymbol $showSymbol
     * @param ShowMinus $showMinus
     * @param CurrencyInterface $localeCurrency
     * @param DefaultFormat $defaultFormat
     * @param LocaleResolver $localeResolver
     * @param CurrencyFactory $currencyFactory
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        DecimalNumber $decimalNumber,
        DecimalSeparator $decimalSeparator,
        GroupSeparator $groupSeparator,
        ShowSymbol $showSymbol,
        ShowMinus $showMinus,
        CurrencyInterface $localeCurrency,
        DefaultFormat $defaultFormat,
        LocaleResolver $localeResolver,
        CurrencyFactory $currencyFactory,
        Currencysymbol $currencySymbol
    ) {
        $this->_decimalNumber = $decimalNumber;
        $this->_decimalSeparator = $decimalSeparator;
        $this->_groupSeparator = $groupSeparator;
        $this->_showSymbol = $showSymbol;
        $this->_showMinus = $showMinus;
        $this->_localeCurrency = $localeCurrency;
        $this->_defaultFormat = $defaultFormat;
        $this->_localeResolver = $localeResolver;
        $this->_currencyFactory = $currencyFactory;
        $this->_currencySymbol = $currencySymbol;

        parent::__construct($context, $objectManager, $storeManager);
    }
    
    /**
     * @return array
     */
    public function getFormatOptions()
    {
        return [
            'decimal_number' => $this->_decimalNumber->toOptionArray(),
            'decimal_separator' => $this->_decimalSeparator->toOptionArray(),
            'group_separator' => $this->_groupSeparator->toOptionArray(),
            'show_symbol' => $this->_showSymbol->toOptionArray(),
            'show_minus' => $this->_showMinus->toOptionArray(),
        ];
    }
    
    /**
     * @param string $currency
     * @return string
     */
    public function getCurrencySymbol($currency)
    {
        $symbol = $this->_localeCurrency->getCurrency($currency)->getSymbol();
        if ($symbol === null) {
            $symbolData = $this->_currencySymbol->getCurrencySymbolsData();
            return $symbolData[$currency]['displaySymbol'];
        }
        
        return $symbol;
    }
    
    /**
     * @param mixed $storeId
     * @return array
     * @throws NoSuchEntityException
     */
    public function getCurrenciesByStore($storeId)
    {
        return $this->storeManager->getStore($storeId)->getAvailableCurrencyCodes();
    }

    /**
     * @param mixed $websiteId
     * @return array
     * @throws NoSuchEntityException
     */
    public function getCurrenciesByWebsite($websiteId)
    {
        $codes = $this->getConfigValue(self::CURRENCY_WEBSITE, $websiteId, ScopeInterface::SCOPE_WEBSITES);
        if ($codes !== null) {
            return explode(',', $codes);
        }

        return $this->getCurrenciesByStore(0);
    }

    /**
     * @param $currencyCode
     * @return array
     */
    public function getCurrencyDefaultConfig($currencyCode)
    {
        $currentLocale = $this->_localeResolver->getLocale();
        $defaultConfig = $this->_defaultFormat->getFormat($currentLocale, $currencyCode);
        
        return [
            'use_default' => 1,
            'decimal_number' => $defaultConfig['requiredPrecision'],
            'decimal_separator' => $defaultConfig['decimalSymbol'],
            'group_separator' => $defaultConfig['groupSymbol'],
            'symbol' => $this->getCurrencySymbol($currencyCode),
            'show_symbol' => ShowSymbol::BEFORE,
            'show_minus' => ShowMinus::BEFORE_SYMBOL,
            'minus_sign' => self::MINUS_SIGN,
        ];
    }
    
    /**
     * @param string $code
     * @param null $storeId
     * @return mixed
     */
    public function getCurrencyConfig($code, $storeId = null)
    {
        $currencyConfig = self::jsonDecode($this->getConfigGeneral('currencies', $storeId));
        if (!isset($currencyConfig[$code])) {
            $currencyConfig[$code] = $this->getCurrencyDefaultConfig($code);
        }
        
        return $currencyConfig[$code];
    }

    /**
     * @param $code
     * @param $storeId
     * @param null $websiteId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getCurrencyWebsiteConfig($code, $storeId, $websiteId = null)
    {
        $websiteId = ($websiteId === null) ? $this->storeManager->getStore($storeId)->getWebsiteId() : $websiteId;
        $config = $this->getConfigValue(
            self::CONFIG_MODULE_PATH . '/general/currencies',
            $websiteId,
            ScopeInterface::SCOPE_WEBSITES
        );
        $currencyConfig = self::jsonDecode($config);

        if (!isset($currencyConfig[$code])) {
            $currencyConfig[$code] = $this->getCurrencyDefaultConfig($code);
        }

        return $currencyConfig[$code];
    }
    
    /**
     * @param string $code
     * @param string $showSymbol
     * @param string $symbol
     * @return string
     */
    public function getLocaleShowSymbol($code, $showSymbol, $symbol)
    {
        $currency = $this->_currencyFactory->create()->load($code);
        $default = $currency->getOutputFormat();
    
        return $this->processShowSymbol($symbol, DefaultFormat::CONTENT, $showSymbol, [], $default);
    }
    
    /**
     * @param string $result
     * @param int $decimal
     * @param array $original
     * @param array $config
     * @return mixed|string
     */
    public function getDirectoryCurrency($result, $decimal, $original, $config)
    {
        $negative = [];
        if (strpos($result, self::MINUS_SIGN) !== false) {
            $negative = [
                'show_minus' => $config['show_minus'],
                'minus_sign' => $config['minus_sign'],
            ];
        }

        if ($decimal === 0) {
            $processedCurrency = $this->processShowSymbol(
                $config['symbol'],
                $result,
                $config['show_symbol'],
                $negative
            );
            return str_replace($original['groupSymbol'], $config['group_separator'], $processedCurrency);
        }

        $decimalPart = substr($result, -($decimal + 1), $decimal + 1);
        $currencyPart = substr($result, 0, strlen($result) - ($decimal + 1));
        $currencyPartResult = str_replace($original['groupSymbol'], $config['group_separator'], $currencyPart);
        $decimalPartResult = str_replace($original['decimalSymbol'], $config['decimal_separator'], $decimalPart);
        $result = $currencyPartResult . $decimalPartResult;

        return $this->processShowSymbol($config['symbol'], $result, $config['show_symbol'], $negative);
    }
    
    /**
     * @param string $symbol
     * @param string $content
     * @param string $options
     * @param array $negative
     * @param null $default
     * @return string
     */
    public function processShowSymbol($symbol, $content, $options, $negative, $default = null)
    {
        if (!empty($negative)) {
            $content = str_replace(self::MINUS_SIGN, '', $content);

            switch ($negative['show_minus']) {
                case ShowMinus::BEFORE_SYMBOL:
                    $symbol = $negative['minus_sign'] . $symbol;
                    break;
                case ShowMinus::AFTER_SYMBOL:
                    $symbol .= $negative['minus_sign'];
                    break;
                case ShowMinus::BEFORE_VALUE:
                    $content = $negative['minus_sign'] . $content;
                    break;
                case ShowMinus::AFTER_VALUE:
                    $content .= $negative['minus_sign'];
                    break;
            }
        }

        switch ($options) {
            case ShowSymbol::BEFORE:
                return $symbol . $content;
            case ShowSymbol::BEFORE_WITH_SPACE:
                return $symbol .' '. $content;
            case ShowSymbol::AFTER:
                return $content . $symbol;
            case ShowSymbol::AFTER_WITH_SPACE:
                return $content .' '. $symbol;
            case ShowSymbol::NONE:
                if (isset($negative['minus_sign'])) {
                    return $negative['minus_sign'] . $content;
                }
                return $content;
        }
        
        if ($default !== null) {
            return $default;
        }

        return $content;
    }
}
