<?php

declare(strict_types=1);

namespace luverelle\pson\attributes;

use Attribute;
use luverelle\pson\exceptions\RequiredPropertyException;
use luverelle\pson\property\AttributeProperty;
use luverelle\pson\PSON;
use ReflectionNamedType;

#[Attribute(Attribute::TARGET_PROPERTY)]
class JsonProperty implements AttributeProcessor{

    public function __construct(
        private string $name,
        private bool $required = true,
        private ?string $arrayValueClass = null
    ){}

    /**
     * @param array<int|string, mixed> $data
     * @param AttributeProperty        $property
     * @throws RequiredPropertyException
     */
    public function processToJson(array &$data, AttributeProperty $property) : void{
        if(!$property->isInitialized() && $this->required){
            throw new RequiredPropertyException(
                "Non-initialized required property " . $property->getName() . " in class " . $property->getClassName()
            );
        }

        $propertyValue = $property->getPropertyValue();
        if(is_object($propertyValue)){
            $data[$this->name] = PSON::toJsonArray($propertyValue);
            return;
        }
        if(is_array($propertyValue)){
            foreach($propertyValue as $value){
                $data[$this->name][] = is_object($value) ? PSON::toJsonArray($value) : $value;
            }
            return;
        }

        $data[$this->name] = $propertyValue;
    }

    /**
     * @param array<int|string, mixed> $data
     * @param AttributeProperty        $property
     * @throws RequiredPropertyException
     */
    public function processFromJson(array &$data, AttributeProperty $property) : void{
        if(!isset($data[$this->name]) && $this->required){
            throw new RequiredPropertyException(
                "Missed required property " . $property->getName() . " in JSON data as " . $this->name . " in class " . $property->getClassName()
            );
        }
        $type = $property->getType();
        if(!$type instanceof ReflectionNamedType){
            return;
        }

        $value = $data[$this->name] ?? null;
        if($type->getName() === "array" && $this->arrayValueClass !== null && class_exists($this->arrayValueClass)){
            $arr = [];
            foreach($value as $object){
                $arr[] = PSON::fromJsonArrayAsClass($object, $this->arrayValueClass);
            }
            $value = $arr;
        }elseif(!$type->isBuiltin() && class_exists($type->getName())){
            $value = PSON::fromJsonArrayAsClass($value, $type->getName());
        }

        $property->setPropertyValue($value);
    }

    /**
     * @return string
     */
    public function getName() : string{
        return $this->name;
    }
}