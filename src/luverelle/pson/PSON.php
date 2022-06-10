<?php

declare(strict_types=1);

namespace luverelle\pson;

use luverelle\pson\exceptions\InvalidClassException;
use luverelle\pson\property\AttributeProperty;
use ReflectionClass;
use ReflectionException;

class PSON{

    /**
     * @param object $object
     * @return array<int|string, mixed>
     */
    public static function toJsonArray(object $object) : array{
        $data = [];
        foreach(self::getAllProperties($object) as $property){
            foreach($property->getAttributes() as $attribute){
                $attribute->processToJson($data, $property);
            }
        }

        return $data;
    }

    /**
     * @param array<int|string, mixed> $data
     * @param object                   $object
     */
    public static function fromJsonArray(array $data, object $object) : void{
        foreach(self::getAllProperties($object) as $property){
            foreach($property->getAttributes() as $attribute){
                $attribute->processFromJson($data, $property);
            }
        }
    }

    /**
     * @template T of object
     * @param array<int|string, mixed> $data
     * @phpstan-param class-string<T>  $class
     * @param string                   $class
     * @return T
     */
    public static function fromJsonArrayAsClass(array $data, string $class) : object{
        try{
            $reflectionClass = new ReflectionClass($class);
            /** @var T $object */
            $object = $reflectionClass->newInstanceWithoutConstructor();
            self::fromJsonArray($data, $object);

            return $object;
        }catch(ReflectionException $e){
            throw new InvalidClassException($e->getMessage());
        }
    }

    /**
     * @param object $object
     * @return AttributeProperty[]
     */
    private static function getAllProperties(object $object) : array{
        $reflectionClass = new ReflectionClass($object);
        $properties = [];
        self::getProperties($object, $reflectionClass, $properties);

        //get all parent properties
        while(($parent = $reflectionClass->getParentClass()) != false){
            self::getProperties($object, $parent, $properties); //why we bind it to object var idk...
            $reflectionClass = $parent;
        }

        return $properties;
    }

    /**
     * @param object              $object
     * @param ReflectionClass     $reflectionClass
     * @param AttributeProperty[] $properties
     */
    private static function getProperties(object $object, ReflectionClass $reflectionClass, array &$properties) : void{
        foreach($reflectionClass->getProperties() as $property){
            $properties[] = new AttributeProperty($object, $property);
        }
    }
}