<?php

namespace Settings\Factory;

use Interop\Container\ContainerInterface;
use Settings\Common\CommonEntity;
use Settings\Common\CommonTableGateway;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class AbstractMapperFactory implements AbstractFactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /**
         * @var CommonTableGateway $requestedName
         * @var CommonEntity $entity
         */

        $entityName = str_replace('Mapper', 'Entity', $requestedName);
        $entity = new $entityName();
        $mapper = new $requestedName($container->get(AdapterInterface::class), $entity);

        return $mapper;
    }

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $entityName = str_replace('Mapper', 'Entity', $requestedName);

        $isClassExists = class_exists($requestedName);
        $isEntityExists = class_exists($entityName);
        $isMapper = preg_match('/^[a-z]+\\\Mapper\\\.*Mapper/i', $requestedName);

        return (
            $isClassExists && $isEntityExists && $isMapper
        );
    }
}
