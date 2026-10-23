<?php

namespace common\models\enum;

use skinka\php\TypeEnum\BaseEnum;

class TaskItem extends BaseEnum
{
    const ADMINISTRATIVE = 1;
    const PLANNING = 2;
    const CREATIVE = 3;
    const DEVELOPMENT = 4;
    const PHOTO = 5;
    const PROJECT_MANAGEMENT = 6;
    const PRELIM_SCOPING = 7;
    const PRODUCTION = 8;
    const ADHOC = 9;
    const EDUCATION = 10;
    const BIZDEV = 11;

    public static function getData()
    {
        return [
            static::ADMINISTRATIVE => ['text' => 'Administrative'],
            static::PLANNING => ['text' => 'Planning'],
            static::CREATIVE => ['text' => 'Creative'],
            static::DEVELOPMENT => ['text' => 'Development'],
            static::PHOTO => ['text' => 'Photography / Photo Search'],
            static::PROJECT_MANAGEMENT => ['text' => 'Project Management'],
            static::PRELIM_SCOPING => ['text' => 'Prelim Scoping'],
            static::PRODUCTION => ['text' => 'Production'],
            static::ADHOC => ['text' => 'Adhoc Production'],
            static::EDUCATION => ['text' => 'Education'],
            static::BIZDEV => ['text' => 'Business Development']
        ];
    }
}
