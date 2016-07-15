<?php

namespace Settings\Common;

use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;

class CommonActionController extends AbstractActionController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
