<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Model\ResourceModel;

use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Opengento\Gdpr\Api\Data\EraseEntityInterface;

/**
 * Class EraseEntity
 */
final class EraseEntity extends AbstractDb
{
    public const TABLE = 'opengento_gdpr_erase_entity';

    /**
     * @inheritdoc
     */
    protected function _construct(): void
    {
        $this->_init(self::TABLE, EraseEntityInterface::ID);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string|array $field
     * @param mixed $value
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\DB\Select
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getLoadSelect($field, $value, $object): Select
    {
        if (!\is_array($field) && !\is_array($value)) {
            return parent::_getLoadSelect($field, $value, $object);
        }

        $select = $this->getConnection()->select()->from($this->getMainTable());

        foreach ($field as $i => $identifier) {
            $pk = $this->getConnection()->quoteIdentifier(\sprintf('%s.%s', $this->getMainTable(), $identifier));
            $select->where($pk . '=?', $value[$i]);
        }

        return $select;
    }
}
