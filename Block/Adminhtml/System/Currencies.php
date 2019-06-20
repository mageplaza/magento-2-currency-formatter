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

namespace Mageplaza\CurrencyFormatter\Block\Adminhtml\System;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Mageplaza\CurrencyFormatter\Helper\Data as HelperData;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Currencies
 * @package Mageplaza\CurrencyFormatter\Block\Adminhtml\System
 */
class Currencies extends AbstractFieldArray
{
    const BASE_SELECT_NAME = 'groups[general][fields][currencies][value]';
    
    /**
     * @var string
     */
    protected $_template = 'Mageplaza_CurrencyFormatter::system/config/currencies.phtml';
    
    /**
     * @var HelperData
     */
    protected $_helperData;
    
    /**
     * @var CurrencyInterface
     */
    protected $_localeCurrency;
    
    /**
     * Currencies constructor.
     * @param Context $context
     * @param HelperData $helperData
     * @param CurrencyInterface $localeCurrency
     * @param array $data
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        CurrencyInterface $localeCurrency,
        array $data = []
    ) {
        $this->_helperData = $helperData;
        $this->_localeCurrency = $localeCurrency;
        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareToRender()
    {
        $this->addColumn('currencies', ['label' => __('Currencies')]);
        $this->addColumn('template', ['label' => __('Template')]);
        $this->addColumn('preview', ['label' => __('Preview')]);
    }
    
    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function getStoreCurrencies()
    {
        $storeCurrencies = [];
        $storeId = $this->getRequest()->getParam('store', 0);
        $availableCurrencies = $this->_helperData->getCurrenciesByStore($storeId);
        
        foreach ($availableCurrencies as $currency) {
            $storeCurrencies[$currency]['code'] = $currency;
            $storeCurrencies[$currency]['name'] = $this->_localeCurrency->getCurrency($currency)->getName();
        }
    
        return $storeCurrencies;
    }
    
    /**
     * @return array
     */
    public function getFormatOptions()
    {
        return $this->_helperData->getFormatOptions();
    }
    
    /**
     * @param string $fieldName
     * @param string $currencyCode
     * @return string
     */
    public function getSelectName($fieldName, $currencyCode)
    {
        return self::BASE_SELECT_NAME . "[{$currencyCode}]" . "[{$fieldName}]";
    }
    
    /**
     * @return array
     */
    public function getUseDefaultConfig()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        if ($storeId !== 0) {
            return [
                'text' => __('Use Website'),
                'remove' => 1,
            ];
        }
        
        $websiteId = (int) $this->getRequest()->getParam('website', 0);
        if ($websiteId !== 0) {
            return [
                'text' => __('Use Default'),
                'remove' => 1,
            ];
        }
        
        return [
            'text' => __('Use System'),
            'remove' => 0,
        ];
    }
}
