<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<h1 class="text-info text-center">DHT11 measurements list</h1>

<div class="col">
    <table class="table table-bordered">
        <thead>
            <th>Temperature</th>
            <th>Humidity</th>
            <th>Date</th>
        </thead>
        <tbody>
        <? foreach ($dhts as $dht): ?>
            <tr >
                <td class="text-center"><? echo $dht->Temperature ?></td>
                <td class="text-center"> <? echo $dht->Humidity ?> </td>
                <td class="text-center"> <? echo $dht->Created_at ?></td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>
</div>
<div class="col">
    <?= LinkPager::widget(['pagination' => $pagination,]) ?>
</div>
