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

namespace Mageplaza\CurrencyFormatter\Model\Core;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Mageplaza\CurrencyFormatter\Helper\Data as HelperData;

/**
 * Get configuration specifically by Scope and Scope Value
 * Class Configuration
 * @package Mageplaza\CurrencyFormatter\Model\Resource
 */
class Configuration
{
    /**
     * @var ResourceConnection
     */
    protected $_resource;

    /**
     * Configuration constructor.
     *
     * @param ResourceConnection $resource
     */
    public function __construct(ResourceConnection $resource)
    {
        $this->_resource = $resource;
    }

    /**
     * @return AdapterInterface
     */
    public function getConnection()
    {
        return $this->_resource->getConnection(ResourceConnection::DEFAULT_CONNECTION);
    }

    /**
     * @return string
     */
    public function getConfigTable()
    {
        return $this->_resource->getTableName('core_config_data');
    }

    /**
     * @param string $scope
     * @param int $scopeId
     *
     * @return array
     */
    public function getConfigByScope($scope, $scopeId)
    {
        $query = $this->getConnection()->select()->from($this->getConfigTable(), 'value')
            ->where('path = ?', HelperData::CONFIG_PATH)
            ->where('scope = ?', $scope)
            ->where('scope_id = ?', $scopeId);
        $result = $this->getConnection()->fetchOne($query);

        if ($result) {
            return HelperData::jsonDecode($result);
        }

        return [];
    }
}
