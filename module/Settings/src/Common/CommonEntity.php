<?php

namespace Settings\Common;

abstract class CommonEntity
{
    private $properties;

    public function __construct()
    {
//        $reflect = new \ReflectionClass($this);
//        $properties = $reflect->getDefaultProperties();
//
//        foreach ($properties as $property => $value) {
//            $this->$property = new Null();
//        }
//
//        $this->properties = $properties;
    }

    public function exchangeArray()
    {
        $propertiesArray = [];

        foreach ($this->properties as $property => $value) {
            $data = $this->$property;//($this->$property instanceof Null) ? null : $this->$property;
            $propertiesArray[$property] = $data;
        }

        return $propertiesArray;
    }
}
