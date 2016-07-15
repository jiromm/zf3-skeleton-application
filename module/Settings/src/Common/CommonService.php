<?php

namespace Settings\Common;

use Interop\Container\ContainerInterface;

class CommonService
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
