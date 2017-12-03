<?php

namespace app\models;

use Yii;
use yii\base\Model;

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