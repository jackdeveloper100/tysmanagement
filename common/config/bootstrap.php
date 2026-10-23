<?php

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@assets', dirname(dirname(__DIR__)) . '/assets');

foreach (array_merge($_ENV, $_SERVER) as $key => $val) {
    if (is_string($val) && getenv($key) === false && function_exists('putenv')) {
        @putenv("$key=$val");
    }
}