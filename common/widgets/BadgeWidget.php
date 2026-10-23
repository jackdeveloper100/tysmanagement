<?php
/**
 * Created by Alex Bond at UPDG.
 * Date: 2/17/15 1:48 AM
 */

namespace common\widgets;


class BadgeWidget
{
    public static function widget( $level, $message, $class = '' )
    {
        return "<span class='badge badge-" . $level . " " . $class . "'></span> " . $message;
    }
}