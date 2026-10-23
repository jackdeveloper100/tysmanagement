<?php

namespace common\models\enum;

use skinka\php\TypeEnum\BaseEnum;

class SubJobStatus extends BaseEnum
{
    const CURRENT = 0;
    const COMPLETED = 1;
    const CANCELED = 2;

    public static function getData()
    {
        return [
            static::CURRENT => ['text' => 'Current'],
            static::COMPLETED => ['text' => 'Completed'],
            static::CANCELED => ['text' => 'Canceled']
        ];
    }
}
