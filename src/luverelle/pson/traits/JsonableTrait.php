<?php

declare(strict_types=1);

namespace luverelle\pson\traits;

use luverelle\pson\PSON;

trait JsonableTrait{

    public function toJsonArray() : array{
        return PSON::toJsonArray($this);
    }

    /**
     * @param array<int|string, mixed> $arr
     * @return $this
     */
    public static function fromJsonArray(array $arr) : static{
        return PSON::fromJsonArrayAsClass($arr, static::class);
    }
}