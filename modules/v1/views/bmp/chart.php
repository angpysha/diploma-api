<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 5/3/2018
 * Time: 11:31 PM
 */
use yii\helpers\Html;
use dosamigos\chartjs\ChartJs;
use yii\widgets\LinkPager;
?>


<div class="container-fluid">
    <h2 class="text-info text-center">Графіки з BMP180 за <?php echo $datee ?></h2>
    <div class="col-sm-12 col-xs-12 col-md-3 col-lg-3 chartstyle">
        <div class="title">Графік температури</div>
        <?= ChartJs::widget([
            'type' => 'line',
            'options' => [
                'height' => 200,
                'width' => 300
            ],
            'data' => [
                'labels' => $dates,
                'datasets' => [
                    [   'label' => 'Температура',
                        'backgroundColor' => "rgba(255,255,255,0)",
                        'borderColor' => "rgba(244,181,198,1)",
                        'pointBackgroundColor' => "rgba(179,181,198,1)",
                        'pointBorderColor' => "#fff",
                        'pointHoverBackgroundColor' => "#fff",
                        'pointHoverBorderColor' => "rgba(179,181,198,1)",
                        'data' => $temps
                    ]
                ]
            ]
        ]);
        ?>
    </div>
    <div class="col-sm-12 col-xs-12 col-md-3 col-lg-3 chartstyle">
        <div class="title">Графік тиску</div>
        <?= ChartJs::widget([
            'type' => 'line',
            'options' => [
                'height' => 200,
                'width' => 300
            ],
            'data' => [
                'labels' => $dates,
                'datasets' => [
                    ['label' => 'Тиск',
                        'backgroundColor' => "rgba(255,255,255,0)",
                        'borderColor' => "rgba(23, 115, 214,1)",
                        'pointBackgroundColor' => "rgba(179,181,198,1)",
                        'pointBorderColor' => "#fff",
                        'pointHoverBackgroundColor' => "#fff",
                        'pointHoverBorderColor' => "rgba(179,181,198,1)",
                        'data' => $pres
                    ]
                ]
            ]
        ]);
        ?>
    </div>
    <div class="col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-2 col-md-6 col-lg-6">
        <div class="btn-group">
            <?php if($pagenum!=1): ?>
                <a class="btn btn-default" href=<?php echo "/web/api/v1/bmps/charts/".strval(1) ?> > <span class="glyphicon glyphicon-fast-backward"></span> </a>
            <?php endif; ?>
            <?php if($pagenum-1>0): ?>
                <a class="btn btn-default" href=<?php echo "/web/api/v1/bmps/charts/".strval($pagenum-1) ?> > <span class="glyphicon glyphicon-step-backward"></span> </a>
            <?php endif; ?>
            <?php if($pagenum-3>0): ?>
            <a class="btn btn-default" href=<?php echo "/web/api/v1/bmps/charts/".strval($pagenum-3) ?> ><?php echo strval($pagenum-3) ?> </a>
            <?php endif; ?>
            <?php if($pagenum-2>0): ?>
                <a class="btn btn-default" href=<?php echo "/web/api/v1/bmps/charts/".strval($pagenum-2) ?> ><?php echo strval($pagenum-2) ?> </a>
            <?php endif; ?>
            <?php if($pagenum-1>0): ?>
                <a class="btn btn-default" href=<?php echo "/web/api/v1/bmps/charts/".strval($pagenum-1) ?> ><?php echo strval($pagenum-1) ?> </a>
            <?php endif; ?>
            <?php if($pagenum>0): ?>
                <a class="btn btn-info btn-disabled" href=<?php echo "/web/api/v1/bmps/charts/".strval($pagenum) ?> ><?php echo strval($pagenum) ?> </a>
            <?php endif; ?>
            <?php if($pagenum+1<$totalpages): ?>
                <a class="btn btn-default" href=<?php echo "/web/api/v1/bmps/charts/".strval($pagenum+1) ?> ><?php echo strval($pagenum+1) ?> </a>
            <?php endif; ?>
            <?php if($pagenum+2<$totalpages): ?>
                <a class="btn btn-default" href=<?php echo "/web/api/v1/bmps/charts/".strval($pagenum+2) ?> ><?php echo strval($pagenum+2) ?> </a>
            <?php endif; ?>
            <?php if($pagenum+3<$totalpages): ?>
                <a class="btn btn-default" href=<?php echo "/web/api/v1/bmps/charts/".strval($pagenum+3) ?> ><?php echo strval($pagenum+3) ?> </a>
            <?php endif; ?>
            <?php if($pagenum+1<$totalpages): ?>
                <a class="btn btn-default" href=<?php echo "/web/api/v1/bmps/charts/".strval($pagenum+1) ?> ><span class="glyphicon glyphicon-step-forward"></span> </a>
            <?php endif; ?>
            <?php if($pagenum!=$totalpages): ?>
                <a class="btn btn-default" href=<?php echo "/web/api/v1/bmps/charts/".strval($totalpages) ?> ><span class="glyphicon glyphicon-fast-forward"> </span> </a>
            <?php endif; ?>
        </div>
    </div>
</div>
