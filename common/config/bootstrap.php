<?php
use Intervention\Image\ImageManagerStatic as Image;

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@assets', dirname(dirname(__DIR__)) . '/assets');
Image::configure(['driver' => 'imagick']);