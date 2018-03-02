<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<h1 class="text-info text-center">BMP180 measurements list</h1>

<div class="col">
    <table class="table table-bordered">
        <thead>
        <th>Temperature</th>
        <th>Altitude</th>
        <th>Pressure</th>
        <th>Date</th>
        </thead>
        <tbody>
        <? foreach ($bmps as $bmp): ?>
            <tr >
                <td class="text-center"><? echo $bmp->Temperature ?></td>
                <td class="text-center"> <? echo $bmp->Altitude ?> </td>
                <td class="text-center"> <? echo $bmp->Pressure ?> </td>
                <td class="text-center"> <? echo $bmp->Created_at ?></td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>
</div>
<div class="col">
    <?= LinkPager::widget(['pagination' => $pagination]) ?>
</div>
