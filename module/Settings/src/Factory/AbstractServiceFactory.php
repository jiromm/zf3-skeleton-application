<?php

namespace Settings\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class AbstractServiceFactory implements AbstractFactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName($container);
    }

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $isClassExists = class_exists($requestedName);
        $isService = preg_match('/^[a-z]+\\\Service\\\.*Service/i', $requestedName);

        return (
            $isClassExists && $isService
        );
    }
}
