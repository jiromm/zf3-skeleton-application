<?php

namespace Settings\Common;

use Settings\Library\Nil;
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
        $prepared = $this->cleanup(
            $this->hydrator->extract($entity)
        );

        return parent::insert($prepared);
    }

    /**
     * @param CommonEntity $entity
     * @param Where|\Closure|string|array $where
     * @return int
     */
    public function update($entity, $where = null)
    {
        $prepared = $this->cleanup(
            $this->hydrator->extract($entity)
        );

        return parent::update($prepared, $where);
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

    /**
     * @param array $inputArray
     * @return array
     */
    protected function cleanup(array $inputArray)
    {
        $output = [];

        if (count($inputArray)) {
            foreach ($inputArray as $prop => $value) {
                if ($value instanceof Nil) {
                    continue;
                }

                $output[$prop] = $value;
            }
        }

        return $output;
    }
}
