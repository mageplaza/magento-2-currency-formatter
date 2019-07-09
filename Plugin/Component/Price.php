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

namespace Mageplaza\CurrencyFormatter\Plugin\Component;

use Magento\Catalog\Ui\Component\Listing\Columns\Price as ComponentPrice;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\Store;
use Mageplaza\CurrencyFormatter\Plugin\AbstractFormat;
use Zend_Currency_Exception;

/**
 * Class Price
 * @package Mageplaza\CurrencyFormatter\Plugin\Component
 */
class Price extends AbstractFormat
{
    /**
     * @param ComponentPrice $subject
     * @param callable $proceed
     * @param array $dataSource
     *
     * @return array
     * @throws NoSuchEntityException
     * @throws Zend_Currency_Exception
     */
    public function aroundPrepareDataSource(ComponentPrice $subject, callable $proceed, array $dataSource)
    {
        if (!$this->_helperData->isEnabled(0)) {
            return $proceed($dataSource);
        }

        if (isset($dataSource['data']['items'])) {
            $store = $this->_storeManager->getStore(Store::DEFAULT_STORE_ID);
            $currency = $store->getBaseCurrencyCode();
            $storeId = $store->getId();
            $fieldName = $subject->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item[$fieldName])) {
                    $item[$fieldName] = $this->formatCurrencyText(
                        $currency,
                        sprintf('%f', $item[$fieldName]),
                        $storeId
                    );
                }
            }
        }

        return $dataSource;
    }
}
