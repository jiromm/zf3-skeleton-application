<?php

namespace Settings\Common;

use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractRestfulController;

class CommonRestfulController extends AbstractRestfulController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
