<?php

namespace app\controllers;

use app\models\DhtData;
use app\models\DhtSearch;
use yii\helpers\Json;
use yii\web\Response;
use yii\filters\AccessControl;

class DhtController extends \yii\web\Controller
{
    public function behaviors()
    {
        return ['access' => [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['login', 'signup','add','search','update'
                    ,'delete','last','get','datecount','first','firstlastdates','','index'],
                    'roles' => ['?'],
                ]
            ]
        ]];
    }

    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAddd()
    {
        // $param1 = Yii::$app->request->post('param1', null);
        // $param2 = Yii::$app->request->post('param2', null);
        $dht = new DhtData();

        $dht->Temperature = "128";

        var_dump($dht);
    }

    public function actionAdd()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        $dht = new DHtData();
        $dht->Temperature = $data["Temperature"];
        $dht->Humidity = $data["Humidity"];
        $dht->Created_at = date("Y-m-d H:i:s", strtotime($data["Created_at"]));
        $dht->Updated_at = date("Y-m-d H:i:s", strtotime($data["Updated_at"]));
        $op_result = $dht->save();
        $res["result"] = $op_result;
        $result = Json::encode($res);
        \Yii::$app->response->content = $result;
    }

    public function actionUpdate($id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        $changed = false;
        $dht = DHtData::findOne($id);

        if ($data["Temperature"] != $dht->Temperature) {
            $dht->Temperature = $data["Temperature"];
            $changed = true;
        }

        if ($data["Humidity"] != $dht->Humidity) {
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


    public function actionDelete($id)
    {

        $dht = DHtData::findOne($id);
        $op_result = $dht->delete();
        $res["result"] = $op_result;
        $result = Json::encode($res);
        \Yii::$app->response->content = $result;
        //var_dump($id);
    }

    public function actionCleanAll()
    {

    }

    /**
     * This function return list of DHT11 data sorted by special filter
     *
     * To make this function  owrk you must to create JSON array and put it in request body
     */
    public function actionSearch()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        //var_dump($data);
        $filter = new DhtSearch($data);
        $records = DhtData::find();
        if ($filter->beginDate && !$filter->Date)
            $records = $records->andWhere(['>=', 'Created_at', $filter->beginDate]);

        if ($filter->endDate && !$filter->Date)
            $records = $records->andWhere(['<=', 'Created_at', $filter->endDate]);


        if ($filter->beginTemperature && !$filter->Temperature)
            $records = $records->andWhere(['>=', 'Temperature', $filter->beginTemperature]);

        if ($filter->endTemperature && !$filter->Temperature)
            $records = $records->andWhere(['<=', 'Temperature', $filter->endTemperature]);

        if ($filter->beginHumidity && !$filter->Humidity)
            $records = $records->andWhere(['>=', 'Humidity', $filter->beginHumidity]);

        if ($filter->endHumidity && !$filter->Humidity)
            $records = $records->andWhere(['<=', 'Humidity', $filter->endHumidity]);

        if ($filter->Humidity)
            $records = $records->andWhere(['=', 'Humidity', $filter->Humidity]);
        if ($filter->Temperature)
            $records = $records->andWhere(['=', 'Temperature', $filter->Temperature]);
        if ($filter->Date)
            $records = $records->andWhere(['=', "Created_at", $filter->Date]);

        $records = $records->asArray()->all();

        header('Content-type:application/json');
        $json = JSON::encode($records);

        \Yii::$app->response->content = $json;
    }


    public function actionGet($id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $dht = DhtData::findOne($id);
        $json = JSON::encode($dht);

        \Yii::$app->response->content = $json;
    }

    public function actionLast()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());

        if ($data) {
            $filter = new DhtSearch($data);
            $records = DhtData::find();
            if ($filter->beginDate)
                $records = $records->andWhere(['>=', 'Created_at', $filter->beginDate]);

            if ($filter->endDate)
                $records = $records->andWhere(['<=', 'Created_at', $filter->endDate]);

            $records = $records->asArray()->orderBy('Created_at DESC')->all();
            $max = $records[0];
            $json = JSON::encode($max);
        } else {
            $max = DhtData::find()->max('id');
            $dht = DhtData::findOne($max);
            $json = JSON::encode($dht);
        }
        \Yii::$app->response->content = $json;
    }

    public function actionFirst()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        if ($data) {
            $filter = new DhtSearch($data);
            $records = DhtData::find();
            if ($filter->beginDate)
                $records = $records->andWhere(['>=', 'Created_at', $filter->beginDate]);

            if ($filter->endDate)
                $records = $records->andWhere(['<=', 'Created_at', $filter->endDate]);

            $records = $records->asArray()->orderBy('Created_at')->all();

            $max = $records[0];
            $json = JSON::encode($max);
        } else {
            $max = DhtData::find()->min('id');
            $dht = DhtData::findOne($max);
            $json = JSON::encode($dht);
        }
        \Yii::$app->response->content = $json;
    }

    public function actionFirstlastdates()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $min = DhtData::find()->min('id');
        $dht_min = DhtData::findOne($min);
        $max = DhtData::find()->max('id');
        $dht_max = DhtData::findOne($max);

        $res["min"] = $dht_min->Created_at;
        $res["max"] = $dht_max->Created_at;

        \Yii::$app->response->content = JSON::encode($res);

    }

    public function actionDatecount()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $min = (new \yii\db\Query())->select('*')
            ->from('DhtData')
            ->orderBy('Created_at')
            ->limit('1')
            ->all()[0]['Created_at'];

        $max = (new \yii\db\Query())->select('*')
            ->from('DhtData')
            ->orderBy('Created_at DESC')
            ->limit('1')
            ->all()[0]['Created_at'];

        $date1 = new \DateTime(date('Y-m-d', strtotime($min)));
        $date2 = new \DateTime(date('Y-m-d', strtotime($max)));
        $diff = $date1->diff($date2)->days;
        $result["pages"] = $diff;

        $ret = Json::encode($result);

        \Yii::$app->response->content = $ret;
    }


}
