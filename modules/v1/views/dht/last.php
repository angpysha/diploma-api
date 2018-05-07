<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 02.02.2018
 * Time: 17:00
 */

use yii\helpers\Html;
use yii\widgets\LinkPager;

?>

<h1 class="text-center text-info">Last sensor data</h1>

<div class="well col-md-4">
    <p class="text-info">Temperature: <span class="text-muted"> <? echo $temp ?></span></p>
    <p class="text-info">Humidity: <span class="text-muted"> <? echo $hum ?></span></p>
    <p class="text-info">Date: <span class="text-muted"> <? echo $date ?></span></p>
</div>
