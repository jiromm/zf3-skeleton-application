<?php

namespace Settings\Common;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;
use Zend\Hydrator\Reflection;

class CommonTableGateway extends TableGateway
{
    /**
     * Possibility to process not nested but non conflicting tansactions
     * @var int $transactionCounter
     */
    static protected $transactionCounter = 0;
    protected $idCol = 'id';
    protected $entityPrototype = null;
    protected $hydrator = null;
    protected $tableName = null;

    /**
     * @var null|AdapterInterface|Adapter
     */
    protected $adapter = null;

    /**
     * @param AdapterInterface $adapter
     * @param CommonEntity $entity
     */
    public function __construct($adapter, $entity)
    {
        parent::__construct(
            $this->tableName,
            $adapter
        );

        $this->hydrator        = new Reflection();
        $this->entityPrototype = $entity;
        $this->adapter         = $adapter;
    }

    /**
     * @param EntityBase|\ArrayObject $entity
     */
    public function setEntity($entity)
    {
        $this->entityPrototype = $entity;
    }

    /**
     * @param ResultSetInterface $results
     * @param bool $one
     * @return \Zend\Db\ResultSet\ResultSet|bool
     */
    protected function hydrate($results, $one = false)
    {
        $result = new HydratingResultSet(
            $this->hydrator,
            $this->entityPrototype
        );

        $hydro = $result->initialize($results->toArray());

        return $one ? $hydro->current() : $hydro;
    }

    /**
     * @param \Closure|\Zend\Db\Sql\Where|array|string $where
     * @param array $columns
     * @param array $order
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($where = null, $columns = [], $order = null)
    {
        if ($where instanceof \Closure) {
            $select = $this->select($where);
        } else {
            $select = $this->select(function (Select $select) use ($where, $columns, $order) {
                if (!is_null($where)) {
                    $select->where($where);
                }

                if (count($columns)) {
                    $select->columns($columns);
                }

                if (!is_null($order)) {
                    $select->order($order);
                }
            });
        }

        return $this->hydrate($select);
    }

    /**
     * @param \Closure|\Zend\Db\Sql\Where|array|string $where
     * @param array $columns
     *
     * @return bool
     */
    public function fetchOne($where = null, $columns = [])
    {
        if ($where instanceof \Closure) {
            $select = $this->select($where);
        } else {
            $select = $this->select(function (Select $select) use ($where, $columns) {
                if (!is_null($where)) {
                    $select->where($where);
                }

                if (count($columns)) {
                    $select->columns($columns);
                }
            });
        }

        return $this->hydrate($select, true);
    }

    /**
     * @param CommonEntity $entity
     * @return int
     */
    public function insert($entity)
    {
        return parent::insert($this->hydrator->extract($entity));
    }

    /**
     * @param CommonEntity $entity
     * @param Where|\Closure|string|array $where
     * @return int
     */
    public function update($entity, $where = null)
    {
        return parent::update($this->hydrator->extract($entity), $where);
    }

    public function beginTransaction()
    {
        if (!self::$transactionCounter) {
            $connection = $this->adapter->getDriver()->getConnection();
            $connection->beginTransaction();
        }

        self::$transactionCounter++;
    }

    public function commitTransaction()
    {
        self::$transactionCounter--;

        if (!self::$transactionCounter) {
            $connection = $this->adapter->getDriver()->getConnection();
            $connection->commit();
        }
    }

    public function rollbackTransaction()
    {
        self::$transactionCounter--;

        if (!self::$transactionCounter) {
            $connection = $this->adapter->getDriver()->getConnection();
            $connection->rollback();
        }
    }

    public function getCount($where = null)
    {
        return $this->fetchOne(function (Select $select) use ($where) {
            $select->columns(['count' => new Expression('COUNT(*)')]);

            if ($where !== null) {
                $select->where($where);
            }
        });
    }

    public function multiInsert($data, $ignore = false)
    {
        $count = 0;

        if (count($data)) {
            $ignore = $ignore ? 'IGNORE' : '';
            $columns = (array)current($data);
            $columns = array_keys($columns);
            $columnsCount = count($columns);
            $platform = $this->adapter->platform;

            foreach ($columns as &$column) {
                $column = $platform->quoteIdentifier($column);
            }

            $columns = '(' . implode(',', $columns) . ')';

            $placeholder = array_fill(0, $columnsCount, '?');
            $placeholder = '(' . implode(',', $placeholder) . ')';
            $placeholder = implode(',', array_fill(0, count($data), $placeholder));

            $values = [];

            foreach ($data as $row) {
                foreach ($row as $value) {
                    array_push($values, $value);
                }
            }

            $table = $platform->quoteIdentifier($this->getTable());
            $q = "INSERT $ignore INTO $table $columns VALUES $placeholder";
            $result = $this->adapter->query($q)->execute($values);
            $count = $result->count();
        }

        return $count;
    }
}
