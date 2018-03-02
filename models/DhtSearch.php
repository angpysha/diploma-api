<?php

namespace app\models;

use Yii;
use yii\base\Model;
/** @SWG\Definition()
 *
 * @SWG\Property(property="id", type="integer")
 * @SWG\Property(property="beginTemperature", type="number")
 * @SWG\Property(property="endTemperature", type="number")
 * @SWG\Property(property="beginHumidity", type="number")
 * @SWG\Property(property="endHumidity", type="number")
 * @SWG\Property(property="beginDate", type="date")
 * @SWG\Property(property="endDate", type="date")
 */
class DhtSearch extends Model {
    public $beginDate;
    public $endDate;
    public $beginTemperature;
    public $endTemperature;
    public $beginHumidity;
    public $endHumidity;
    public $Date;
    public $Temperature;
    public $Humidity;

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