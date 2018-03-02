<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 02.03.2018
 * Time: 22:29
 */

namespace app\modules\v1\models;


use yii\base\Model;

/** @SWG\Definition()
 *
 * @SWG\Property(property="id", type="integer")
 * @SWG\Property(property="beginTemperature", type="number")
 * @SWG\Property(property="endTemperature", type="number")
 * @SWG\Property(property="beginPressure", type="number")
 * @SWG\Property(property="endPressure", type="number")
 * @SWG\Property(property="beginAltitude", type="number")
 * @SWG\Property(property="endAltitude", type="number")
 * @SWG\Property(property="beginDate", type="date")
 * @SWG\Property(property="endDate", type="date")
 */
class BmpSearch
{
    public $beginDate;
    public $endDate;
    public $beginTemperature;
    public $endTemperature;
    public $beginPressure;
    public $endPressure;
    public $beginAltitude;
    public $endAltitude;

    public function __construct($json = false) {
        if ($json) {
            if ($json->data)
                $this->set($json->data, true);
            else
                $this->set($json, true);
        }
    }

    public function set($data) {
        foreach ($data AS $key => $value) {
            if (is_array($value)) {
                $sub = new JSONObject;
                $sub->set($value);
                $value = $sub;
            }
            $this->{$key} = $value;
        }
    }
}