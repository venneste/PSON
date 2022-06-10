<?php

declare(strict_types=1);

namespace luverelle\pson\property;

use JetBrains\PhpStorm\Pure;
use luverelle\pson\attributes\AttributeProcessor;
use ReflectionProperty;
use ReflectionType;

class AttributeProperty{

    private object $object;
    private ReflectionProperty $reflectionProperty;

    /**
     * @var array<class-string<AttributeProcessor>, AttributeProcessor>
     */
    private array $attributes;

    public function __construct(object $sourceObject, ReflectionProperty $reflectionProperty){
        $this->object = $sourceObject;
        $this->reflectionProperty = $reflectionProperty;

        $attributes = [];
        foreach($reflectionProperty->getAttributes() as $reflectionAttribute){
            $attribute = $reflectionAttribute->newInstance();
            if($attribute instanceof AttributeProcessor){
                $attributes[get_class($attribute)] = $attribute;
            }
        }
        $this->attributes = $attributes;

        $reflectionProperty->setAccessible(true);
    }

    #[Pure]
    public function getName() : string{
        return $this->reflectionProperty->getName();
    }

    #[Pure]
    public function getType() : ?ReflectionType{
        return $this->reflectionProperty->getType();
    }

    #[Pure]
    public function getPropertyValue() : mixed{
        return $this->reflectionProperty->getValue($this->object);
    }

    public function setPropertyValue(mixed $value) : void{
        $this->reflectionProperty->setValue($this->object, $value);
    }

    #[Pure]
    public function isInitialized() : bool{
        return $this->reflectionProperty->isInitialized($this->object);
    }

    /**
     * @return AttributeProcessor[]
     */
    public function getAttributes() : array{
        return $this->attributes;
    }

    /**
     * @param class-string<AttributeProcessor> $className
     * @return AttributeProcessor|null
     */
    public function getAttribute(string $className) : ?AttributeProcessor{
        return $this->attributes[$className] ?? null;
    }

    /**
     * @return ReflectionProperty
     */
    public function getReflectionProperty() : ReflectionProperty{
        return $this->reflectionProperty;
    }

    /**
     * @return object
     */
    public function getObject() : object{
        return $this->object;
    }

    public function getClassName() : string{
        return get_class($this->object);
    }

    public function __destruct(){
        $this->reflectionProperty->setAccessible(false);
    }
}