<?php

declare(strict_types=1);

namespace luverelle\pson\attributes;

use luverelle\pson\property\AttributeProperty;

interface AttributeProcessor{

    /**
     * @param array<int|string, mixed> $data
     * @param AttributeProperty        $property
     */
    public function processToJson(array &$data, AttributeProperty $property) : void;

    /**
     * @param array<int|string, mixed> $data
     * @param AttributeProperty        $property
     */
    public function processFromJson(array &$data, AttributeProperty $property) : void;
}