<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 13.01.2018
 * Time: 18:11
 */

namespace app\models;


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