<?php
namespace common\models\enum;

class HoursEnum
{
    public static function getOptions()
    {
        $out = [];
        for ($i = 0; $i <= 11; $i++) {
            $out[$i . '.5'] = $i . ':30';
            $out[$i + 1 . ''] = $i + 1 . ':00';
        }
        return $out;
    }
}
