<?php

namespace app\utils;

class Helper
{
    public static function getInitialNames($name)
    {
        $names = explode(' ', $name);
        if (count($names) >= 2) {
            return substr($names[0], 0, 1) . substr($names[1], 0, 1);
        }
        return substr($names[0], 0, 2);
    }


    public static function getPercentage($total, $value)
    {
        return (int)  (($value / $total) * 100);
    }


    public static function loadImage($url)
    {
        if ($url != null && strlen(trim($url)) > 8) {
            // TODO: also check if the image link isn't broken
            return $url;
        }
        return "../static/images/no-image.jpeg";
    }
}
