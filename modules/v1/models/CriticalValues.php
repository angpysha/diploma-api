<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 02.03.2018
 * Time: 22:24
 */

namespace app\modules\v1\models;


class CriticalValues
{
    public static $maxTemperature = 27;
    public static $minTemperature = 18;

    public static $maxHumidity = 75;
    public static $minHumidity = 20;

    public static $maxPressure = 120000;
    public static $minPressure = 95000;
}