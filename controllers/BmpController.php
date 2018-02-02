<?php

namespace app\controllers;

use app\models\Bmp180;
use app\models\BmpSearch;
use yii\helpers\Json;
use yii\web\Response;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use yii\filters\AccessControl;

class BmpController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
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
    public function actionTest()
    {
        var_dump("zzzzz");
    }

    public function actionAdd()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        $bmp = new Bmp180();
        $bmp->Temperature = $data["Temperature"];
        $bmp->Altitude = $data["Altitude"];
        $bmp->Pressure = $data["Pressure"];
        if ($data["Created_at"])
            $bmp->Created_at = date("Y-m-d H:i:s", strtotime($data["Created_at"]));
        else
            $bmp->Created_at = date("Y-m-d H:i:s");
        if ($data["Updated_at"])
            $bmp->Updated_at = date("Y-m-d H:i:s", strtotime($data["Updated_at"]));
        else
            $bmp->Updated_at = date("Y-m-d H:i:s");
        $op_result = $bmp->save();
        $res["result"] = $op_result;
        $result = Json::encode($res);
        \Yii::$app->response->content = $result;
    }

    function actionUpdate($id) {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        $changed = false;
        $bmp = Bmp180::findOne($id);

        if ($data["Temperature"] != $bmp->Temperature)
        {
            $bmp->Temperature = $data["Temperature"];
            $changed = true;
        }

        if ($data["Pressure"] != $bmp->Pressure)
        {
            $bmp->Pressure = $data["Pressure"];
            $changed = true;
        }

        if ($data["Altitude"] != $bmp->Altitude)
        {
            $bmp->Altitude = $data["Altitude"];
            $changed = true;
        }

        if ($changed) {
            $op_result = $bmp->save();

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

        $bmp = Bmp180::findOne($id);
        $op_result = $bmp->delete();
        $res["result"] = $op_result;
        $result = Json::encode($res);
        \Yii::$app->response->content = $result;
        //var_dump($id);
    }


    public function actionSearch() {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        //var_dump($data);
        $filter = new BmpSearch($data);
        $records = Bmp180::find();
        if ($filter->beginDate)
            $records = $records->andWhere(['>=','Created_at',$filter->beginDate]);

        if ($filter->endDate)
            $records = $records->andWhere(['<=','Created_at',$filter->endDate]);


        if ($filter->beginTemperature)
            $records = $records->andWhere(['>=','Temperature',$filter->beginTemperature]);

        if ($filter->endTemperature)
            $records = $records->andWhere(['<=','Temperature',$filter->endTemperature]);

        if ($filter->beginPressure)
            $records = $records->andWhere(['>=','Pressure',$filter->beginPressure]);

        if ($filter->endPressure)
            $records = $records->andWhere(['<=','Pressure',$filter->endPressure]);

        if ($filter->beginAltitude)
            $records = $records->andWhere(['>=','Altitude',$filter->beginAltitude]);

        if ($filter->endAltitude)
            $records = $records->andWhere(['<=','Altitude',$filter->endAltitude]);


        $records = $records->asArray()->all();

        header('Content-type:application/json');
        $json = JSON::encode($records);

        \Yii::$app->response->content =$json;
    }


    public function actionGet($id) {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $bmp = Bmp180::findOne($id);
        $json = JSON::encode($bmp);

        \Yii::$app->response->content =$json;
    }

    public function actionLast() {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        if ($data) {
            $filter = new BmpSearch($data);
            $records = Bmp180::find();
            if ($filter->beginDate)
                $records = $records->andWhere(['>=','Created_at',$filter->beginDate]);

            if ($filter->endDate)
                $records = $records->andWhere(['<=','Created_at',$filter->endDate]);

            $records = $records->asArray()->orderBy('Created_at DESC')->all();
            $max = $records[0];
            $json = JSON::encode($max);
        } else {
            $max = Bmp180::find()->max('id');
            $bmp = Bmp180::findOne($max);
            $json = JSON::encode($bmp);
        }
        \Yii::$app->response->content = $json;
    }

    public function actionFirst() {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        if ($data) {
            $filter = new BmpSearch($data);
            $records = Bmp180::find();
            if ($filter->beginDate)
                $records = $records->andWhere(['>=','Created_at',$filter->beginDate]);

            if ($filter->endDate)
                $records = $records->andWhere(['<=','Created_at',$filter->endDate]);

            $records = $records->asArray()->orderBy('Created_at')->all();
            $max = $records[0];
            $json = JSON::encode($max);
        } else {
            $max = Bmp180::find()->min('id');
            $bmp = Bmp180::findOne($max);
            $json = JSON::encode($bmp);
        }
        \Yii::$app->response->content = $json;
    }

    public function actionFirstlastdates() {
        $min = Bmp180::find()->min('id');
        $bmp_min = Bmp180::findOne($min);
        $max = Bmp180::find()->max('id');
        $bmp_max = Bmp180::findOne($max);

        $res["min"] = $bmp_min->Created_at;
        $res["max"] = $bmp_max->Created_at;

        return JSON::encode($res);

    }

    public function actionDatecount() {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $min = (new \yii\db\Query())->select('*')
                    ->from('Bmp180')
                    ->orderBy('Created_at')
                    ->limit('1')
                    ->all()[0]['Created_at'];

        $max=(new \yii\db\Query())->select('*')
            ->from('Bmp180')
            ->orderBy('Created_at DESC')
            ->limit('1')
            ->all()[0]['Created_at'];
//        $res['min'] = $min;
//        $res['max'] = $max;
        $date1 = new \DateTime(date('Y-m-d',strtotime($min)));
        $date2 = new \DateTime(date('Y-m-d',strtotime($max)));
        $diff = $date1->diff($date2)->days;
        $result["pages"] = $diff;
        \Yii::$app->response->content = Json::encode($result);
//        var_dump($diff);
    }



    public function actionSendevent() {
        $client = new Client(new Version2X('http://127.0.0.1:3000'));

        $client->initialize();
        $client->emit('test',['foo' => 'bar']);
        $client->close();
    }
}
