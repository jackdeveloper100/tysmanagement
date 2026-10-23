<?php
namespace common\models\enum;

use skinka\php\TypeEnum\BaseEnum;

class JobStatus extends BaseEnum
{
    const ESTIMATE = 1;
    const CURRENT = 2;
    const ON_HOLD = 3;
    const COMPLETED = 4;
    const CANCELED = 5;
    const ARCHIVED = 6;
    const HOSTING = 7;

    public static function getData()
    {
        return [
            static::ESTIMATE => ['text' => 'Estimate'],
            static::CURRENT => ['text' => 'Current'],
            static::HOSTING => ['text' => 'Hosting'],
            static::ON_HOLD => ['text' => 'On hold'],
            //static::COMPLETED => ['text' => 'Completed'],
            static::CANCELED => ['text' => 'Canceled'],
            static::ARCHIVED => ['text' => 'Archived'],
        ];
    }
}
