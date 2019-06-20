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
use Magento\Framework\Locale\Format as LocaleFormat;
use Magento\Directory\Model\CurrencyFactory;
use NumberFormatter;

/**
 * Class Data
 * @package Mageplaza\CurrencyFormatter\Helper
 */
class Data extends AbstractData
{
    const CONFIG_MODULE_PATH = 'mpcurrencyformatter';
    const MINUS_SIGN = '-';
    const CONTENT = '%s';
    
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
     * @var LocaleFormat
     */
    protected $_localeFormat;
    
    /**
     * @var LocaleResolver
     */
    protected $_localeResolver;
    
    /**
     * @var CurrencyFactory
     */
    protected $_currencyFactory;
    
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
     * @param LocaleFormat $localeFormat
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
        LocaleFormat $localeFormat,
        LocaleResolver $localeResolver,
        CurrencyFactory $currencyFactory
    ) {
        $this->_decimalNumber = $decimalNumber;
        $this->_decimalSeparator = $decimalSeparator;
        $this->_groupSeparator = $groupSeparator;
        $this->_showSymbol = $showSymbol;
        $this->_showMinus = $showMinus;
        $this->_localeCurrency = $localeCurrency;
        $this->_localeFormat = $localeFormat;
        $this->_localeResolver = $localeResolver;
        $this->_currencyFactory = $currencyFactory;
    
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
        return $this->_localeCurrency->getCurrency($currency)->getSymbol();
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
     * @param string $currencyCode
     * @return array
     */
    public function getCurrencyDefaultConfig($currencyCode)
    {
        $currentLocale = $this->_localeResolver->getLocale();
        $defaultConfig = $this->_localeFormat->getPriceFormat($currentLocale, $currencyCode);
        
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
     * @param string $code
     * @param string $showSymbol
     * @param string $symbol
     * @return string
     */
    public function getLocaleShowSymbol($code, $showSymbol, $symbol)
    {
        $currency = $this->_currencyFactory->create()->load($code);
        $default = $currency->getOutputFormat();
    
        return $this->processShowSymbol($symbol, self::CONTENT, $showSymbol, $default);
    }
    
    /**
     * @param string $localeCode
     * @param string $currencyCode
     * @return array
     */
    public function getOriginalFormat($localeCode, $currencyCode)
    {
        $formatter = new NumberFormatter(
            $localeCode . '@currency=' . $currencyCode,
            NumberFormatter::CURRENCY
        );
        
        return [
            'decimalSymbol' => $formatter->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL),
            'groupSymbol' => $formatter->getSymbol(NumberFormatter::GROUPING_SEPARATOR_SYMBOL),
        ];
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
        if ($decimal === 0) {
            return $this->processShowSymbol($config['symbol'], $result, $config['show_symbol']);
        }
    
        $decimalPart = substr($result, -($decimal + 1), $decimal + 1);
        $currencyPart = substr($result, 0, strlen($result) - ($decimal + 1));
        $currencyPartResult = str_replace($original['groupSymbol'], $config['group_separator'], $currencyPart);
        $decimalPartResult = str_replace($original['decimalSymbol'], $config['decimal_separator'], $decimalPart);
        $result = $currencyPartResult . $decimalPartResult;
    
        return $this->processShowSymbol($config['symbol'], $result, $config['show_symbol']);
    }
    
    /**
     * @param string $symbol
     * @param string $content
     * @param string $options
     * @param null $default
     * @return string
     */
    public function processShowSymbol($symbol, $content, $options, $default = null)
    {
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
                return $content;
        }
        
        if ($default !== null) {
            return $default;
        }
        
        return $content;
    }
}
