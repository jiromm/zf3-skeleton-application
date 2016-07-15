<?php

namespace Settings\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\Stdlib\DispatchableInterface;

class AbstractControllerFactory implements AbstractFactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName($container);
    }

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $isClassExists = class_exists($requestedName);
        $isDispatchable = in_array(DispatchableInterface::class, class_implements($requestedName));
        $isController = preg_match('/^[a-z]+\\\Controller\\\.*Controller/i', $requestedName);

        return (
            $isClassExists && $isDispatchable && $isController
        );
    }
}
