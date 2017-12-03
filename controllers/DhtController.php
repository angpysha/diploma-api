<?php

namespace app\controllers;

use app\models\dhtData;
use app\models\dhtSearch;
use yii\helpers\Json;
use yii\web\Response;

class DhtController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAddd() {
        // $param1 = Yii::$app->request->post('param1', null);
        // $param2 = Yii::$app->request->post('param2', null);
        $dht = new DhtData();
        
        $dht->Temperature = "128";

        var_dump($dht);
    }

    public function actionAdd() {
        //$data = \Yii::$app->getRequest();
        \Yii::$app->response->format = Response::FORMAT_JSON;        
         $data = Json::decode(\Yii::$app->request->getRawBody());
  //       \xdebug_break();
      //   return 'echo';
         $dht = new DHtData();
         $dht->attributes = $data;
         $dht->save();
         header('Content-type:application/json');
         
        //  $dht2 = DHtData::find(18)->all();
        //  var_dump($dht_2);
         \Yii::$app->response->content = "{\"fasdf\":5}";
        // var_dump($dht);
       // echo 'Hello';
    }

    public function actionUpdate() {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        $changed = false;
        $dht = DHtData::findOne($data['id']);

        if ($data["Temperature"] != $dht->Temperature)
        {
            $dht->Temperature = $data["Temperature"];
            $changed = true;
        }

        if ($data["Humidity"] != $dht->Humidity)
        {
            $dht->Humidity = $data["Humidity"];
            $changed = true;
        }

        if ($changed)
            $dht->save();
        var_dump($dht);
    }

    public function actionDelete($id) {

        $dht = DHtData::findOne($data['id']);
        $dht->delete();
        //var_dump($id);
    }

    public function actionCleanAll() {

    }


    public function actionSearch() {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        //var_dump($data);
  //       $filter = new DhtSearch($data);
      //    $records = DhtData::find();
          \Yii::$app->response->content = \Yii::$app->request->getRawBody();
        //  if ($filter->beginDate && $filter->Date == null)
        //     $records = $records->where(['>=','Created_at',$filter->beginDate]);

        // if ($filter->endDate && $filter->Date == null)
        //     $records = $records->where(['<=','Created_at',$filter->endDate]);

        // if ($filter->beginTemperature && $filter->Temperature == null)
        //     $records = $records->where(['>=','Temperature',$filter->beginTemperature]);

        // if ($filter->endTemperature && $filter->Temperature == null)
        //     $records = $records->where(['<=','Temperature',$filter->endTemperature]);

        // if ($filter->beginHumidity && $filter->Humidity == null )
        //     $records = $records->where(['>=','Humidity',$filter->beginHumidity]);

        // if ($filter->endHumidity && $filter->Humidity == null)
        //     $records = $records->where(['<=','Humidity',$filter->endHumidity]);
        
        
        // $records = $records->asArray()->all();
   
        // header('Content-type:application/json');
        // $json = JSON::encode($records);

        //var_dump($json);
    }

}
