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

namespace Mageplaza\CurrencyFormatter\Plugin\Sale\Order\Search;

use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\Price as PriceRenderer;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\CurrencyFormatter\Helper\Data as HelperData;
use Mageplaza\CurrencyFormatter\Model\Locale\DefaultFormat;
use Mageplaza\CurrencyFormatter\Plugin\AbstractFormat;
use Magento\Framework\DataObject;
use Mageplaza\CurrencyFormatter\Block\Widget\Renderer\Price as BlockPrice;

/**
 * Class Price
 * @package Mageplaza\CurrencyFormatter\Plugin\Sale\Order\Search
 */
class Price extends AbstractFormat
{
    /**
     * @var BlockPrice
     */
    protected $_blockPrice;

    /**
     * Price constructor.
     * @param StoreManagerInterface $storeManager
     * @param HelperData $helperData
     * @param ResolverInterface $localeResolver
     * @param CurrencyInterface $localeCurrency
     * @param FormatInterface $localeFormat
     * @param DefaultFormat $defaultFormat
     * @param BlockPrice $blockPrice
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        HelperData $helperData,
        ResolverInterface $localeResolver,
        CurrencyInterface $localeCurrency,
        FormatInterface $localeFormat,
        DefaultFormat $defaultFormat,
        BlockPrice $blockPrice
    ) {
        $this->_blockPrice = $blockPrice;
        parent::__construct(
            $storeManager,
            $helperData,
            $localeResolver,
            $localeCurrency,
            $localeFormat,
            $defaultFormat
        );
    }

    /**
     * @param PriceRenderer $subject
     * @param callable $proceed
     * @param DataObject $row
     * @return float|int|mixed|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Zend_Currency_Exception
     */
    public function aroundRender(PriceRenderer $subject, callable $proceed, DataObject $row)
    {
        if (!$this->_helperData->isEnabled(0)) {
            return $proceed($row);
        }
        
        if ($data = $this->_blockPrice->getValue($row)) {
            $currencyCode = $this->_blockPrice->getCurrencyCode($row);

            if (!$currencyCode) {
                return $data;
            }
            $storeId = $this->_helperData->isAdmin() ? 0 : null;
            $data = (float)$data * $this->_blockPrice->getRate($row);
            $data = sprintf('%f', $data);
            $data = $this->formatCurrencyText($currencyCode, $data, $storeId);
            return $data;
        }
        return $subject->getColumn()->getDefault();
    }
}