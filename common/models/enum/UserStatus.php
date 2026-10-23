<?php
namespace common\models\enum;

use yii\bootstrap\Html;

/**
 * Class to enumerations of user status
 *
 * @method static UserStatus ACTIVE()
 * @method static UserStatus DISABLED()
 * @method string text()
 */
class UserStatus extends \skinka\php\TypeEnum\BaseEnum
{
    const ACTIVE = 1;
    const DISABLED = 2;

    public static function getData()
    {
        return [
            self::ACTIVE => [
                'text' => 'Active',
                'class' => 'label label-success label-sm',
            ],
            self::DISABLED => [
                'text' => 'Disabled',
                'class' => 'label label-danger label-sm',
            ]
        ];
    }

    public static function getObjectValue($val)
    {
        return Html::tag('span', static::getByValue($val)->text(), ['class' => static::getByValue($val)->class()]);
    }
}
