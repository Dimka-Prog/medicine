<?php

declare(strict_types=1);

namespace App\Services;

class KnowledgeBase
{
    protected static array $arrayData;

    public static function setArray(array $arrayData): void
    {
        self::$arrayData = $arrayData;
    }
}
