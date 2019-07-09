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
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Store\Model\ScopeInterface;
use Mageplaza\CurrencyFormatter\Helper\Data as HelperData;

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
     *
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
     * @return string
     * @throws NoSuchEntityException
     */
    public function getAllowedCurrencies()
    {
        $mpCurrencies = [];
        $scopeData = $this->getScopeData($this->getRequest()->getParams());
        $availableCurrencies = $this->_helperData->getAllowedCurrenciesByScope($scopeData);

        foreach ($availableCurrencies as $code) {
            $mpCurrencies[$code]['code'] = $code;
            $mpCurrencies[$code]['name'] = $this->_localeCurrency->getCurrency($code)->getName();
            $mpCurrencies[$code]['config'] = $this->_helperData->getSavedConfig($code, $scopeData);
            $mpCurrencies[$code]['default'] = $scopeData['defaultTxt'];
            $mpCurrencies[$code]['base'] = self::BASE_SELECT_NAME;
        }

        return HelperData::jsonEncode(array_values($mpCurrencies));
    }

    /**
     * @return string
     */
    public function getFormatOptions()
    {
        return HelperData::jsonEncode($this->_helperData->getFormatOptions());
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function getScopeData($params)
    {
        if (isset($params['website']) && (int) $params['website'] !== 0) {
            return [
                'id'         => $params['website'],
                'defaultTxt' => __('Use Default'),
                'type'       => ScopeInterface::SCOPE_WEBSITES
            ];
        }

        if (isset($params['store']) && (int) $params['store'] !== 0) {
            return [
                'id'         => $params['store'],
                'defaultTxt' => __('Use Website'),
                'type'       => ScopeInterface::SCOPE_STORES
            ];
        }

        return [
            'id'         => 0,
            'defaultTxt' => __('Use System'),
            'type'       => ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ];
    }
}
