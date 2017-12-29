<?php

namespace app\controllers;

use app\models\DhtData;
use app\models\DhtSearch;
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
        \Yii::$app->response->format = Response::FORMAT_JSON;        
         $data = Json::decode(\Yii::$app->request->getRawBody());
         $dht = new DHtData();
         $dht->attributes = $data;
         $op_result = $dht->save();
         header('Content-type:application/json');
        $res["result"] = $op_result;
        $result = Json::encode($res);
         \Yii::$app->response->content = $result;
    }

    public function actionUpdate($id) {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        $changed = false;
        $dht = DHtData::findOne($id);

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

        if ($changed) {
            $op_result = $dht->save();

            $res["result"] = $op_result;
            $result = Json::encode($res);
            \Yii::$app->response->content = $result;
        } else {
            $res["result"] = false;
            $result = Json::encode($res);
            \Yii::$app->response->content = $result;
        }

            
    }


    public function actionDelete($id) {

        $dht = DHtData::findOne($id);
        $op_result = $dht->delete();
        $res["result"] = $op_result;
        $result = Json::encode($res);
         \Yii::$app->response->content = $result;
        //var_dump($id);
    }

    public function actionCleanAll() {

    }

    /**
     * This function return list of DHT11 data sorted by special filter
     * 
     * To make this function  owrk you must to create JSON array and put it in request body
     */
    public function actionSearch() {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        //var_dump($data);
        $filter = new DhtSearch($data);
         $records = DhtData::find();
         if ($filter->beginDate && !$filter->Date)
            $records = $records->andWhere(['>=','Created_at',$filter->beginDate]);

        if ($filter->endDate && !$filter->Date)
            $records = $records->andWhere(['<=','Created_at',$filter->endDate]);
        

         if ($filter->beginTemperature && !$filter->Temperature)
             $records = $records->andWhere(['>=','Temperature',$filter->beginTemperature]);

        if ($filter->endTemperature && !$filter->Temperature)
            $records = $records->andWhere(['<=','Temperature',$filter->endTemperature]);

        if ($filter->beginHumidity && !$filter->Humidity)
            $records = $records->andWhere(['>=','Humidity',$filter->beginHumidity]);

        if ($filter->endHumidity && !$filter->Humidity)
            $records = $records->andWhere(['<=','Humidity',$filter->endHumidity]);
        
        if ($filter->Humidity)
            $records = $records->andWhere(['=','Humidity',$filter->Humidity]);
        if ($filter->Temperature)
            $records = $records->andWhere(['=','Temperature',$filter->Temperature]);
        if ($filter->Date)
            $records = $records->andWhere(['=',"Created_at",$filter->Date]);
        
        $records = $records->asArray()->all();
   
        header('Content-type:application/json');
        $json = JSON::encode($records);

        \Yii::$app->response->content =$json;
    }


    public function actionGet($id) {
        \Yii::$app->response->format = Response::FORMAT_JSON;        
        $dht = DhtData::findOne($id);
        $json = JSON::encode($dht);

        \Yii::$app->response->content =$json;
    }

    public function actionLast() {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $max = DhtData::find()->max('id');
        $dht = DhtData::findOne($max);
        $json = JSON::encode($dht);

        \Yii::$app->response->content = $json;
    }




}
