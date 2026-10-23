<?php
/**
 * Created by Alex Bond at UPDG.
 * Date: 7/1/15 7:40 PM
 */

namespace common\helpers;

use DateTime;

class AlexBondHelper
{
    public static function hoursRange($lower = 0, $upper = 86400, $step = 3600, $format = '')
    {
        $times = [];

        if (empty($format)) {
            $format = 'g:i a';
        }

        foreach (range($lower, $upper, $step) as $increment) {
            $increment = gmdate('H:i', $increment);

            list($hour, $minutes) = explode(':', $increment);

            $date = new DateTime($hour . ':' . $minutes);

            $times[(string)$increment] = $date->format($format);
        }

        return $times;
    }

    /**
     * ucfirst UTF-8 aware function
     *
     * @param string $string
     * @param string $e Encoding
     * @return string
     * @see http://ca.php.net/ucfirst
     */
    public static function ucfirst($string, $e = 'utf-8')
    {
        if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($string)) {
            $string = mb_strtolower($string, $e);
            $upper = mb_strtoupper($string, $e);
            preg_match('#(.)#us', $upper, $matches);
            $string = $matches[1] . mb_substr($string, 1, mb_strlen($string, $e), $e);
        } else {
            $string = ucfirst($string);
        }
        return $string;
    }

    public static function handleShortcodes($content, $shortcodes)
    {
        //Loop through all shortcodes
        foreach ($shortcodes as $key => $function) {
            $dat = [];
            preg_match_all("/\[" . $key . " (.+?)\]/", $content, $dat);
            if (count($dat) > 0 && $dat[0] != [] && isset($dat[1])) {
                $i = 0;
                $actual_string = $dat[0];
                foreach ($dat[1] as $temp) {
                    $temp = explode(" ", $temp);
                    $params = [];
                    foreach ($temp as $d) {
                        list($opt, $val) = explode("=", $d);
                        $params[$opt] = trim($val, '"');
                    }
                    $content = str_replace($actual_string[$i], $function($params), $content);
                    $i++;
                }
            }
        }
        return $content;
    }

    const DATE_FORMAT = 'php:Y-m-d';
    const DATETIME_FORMAT = 'php:Y-m-d H:i:s';
    const TIME_FORMAT = 'php:H:i:s';

    public static function convertFormatForModel($dateStr, $type = 'date', $format = null)
    {
        if ($type === 'datetime') {
            $fmt = ($format == null) ? self::DATETIME_FORMAT : $format;
        } elseif ($type === 'time') {
            $fmt = ($format == null) ? self::TIME_FORMAT : $format;
        } else {
            $fmt = ($format == null) ? self::DATE_FORMAT : $format;
        }
        return \Yii::$app->formatter->asDate($dateStr, $fmt);
    }
}
