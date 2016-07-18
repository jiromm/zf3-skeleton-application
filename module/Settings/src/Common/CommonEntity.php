<?php

namespace Settings\Common;

use Settings\Library\Nil;

abstract class CommonEntity
{
    private $properties;

    public function __construct()
    {
        $reflect = new \ReflectionClass($this);
        $properties = $reflect->getDefaultProperties();

        foreach ($properties as $property => $value) {
            $this->$property = new Nil();
        }

        $this->properties = $properties;
    }

    public function exchangeArray()
    {
        $propertiesArray = [];

        foreach ($this->properties as $property => $value) {
            $data = ($this->$property instanceof Nil) ? null : $this->$property;
            $propertiesArray[$property] = $data;
        }

        return $propertiesArray;
    }
}
