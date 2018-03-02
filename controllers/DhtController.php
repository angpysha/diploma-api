<?php

namespace app\controllers;

use app\models\DhtData;
use app\models\DhtSearch;
use yii\helpers\Json;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\data\Pagination;
use app\models\CriticalValues;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use yii\filters\auth\QueryParamAuth;

/**
 * @SWG\Parameter(parameter="entity_id", name="id", type="integer", in="path")
 */
class DhtController extends \yii\web\Controller implements ISensorController
{

//    protected function verbs()
//    {
//        return [
//            'index' => ['GET'],
//        ];
//    }

//    public function behaviors()
//    {
//        $behaviors= parent::behaviors();
//        $behaviors['authenticator'] = [
//            'class'=> QueryParamAuth::className(),
//        ];
//        return $behaviors;
//    }

    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $query = DhtData::find();

        $pagination = new Pagination(['defaultPageSize' => 15,
            'totalCount' => $query->count(),
        ]);
        $dhts = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'dhts' => $dhts,
            'pagination' => $pagination
        ]);
    }

    public function actionAddd()
    {
        // $param1 = Yii::$app->request->post('param1', null);
        // $param2 = Yii::$app->request->post('param2', null);
        $dht = new DhtData();

        $dht->Temperature = "128";

        var_dump($dht);
    }

    /**
     * @SWG\Post(path="/dhts/add",
     *     tags={"DhtData"},
     *     summary="Add dht data",
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *     @SWG\Parameter(
     *        in = "body",
     *        name = "body",
     *        description = "body",
     *        required = true,
     *         @SWG\Schema(ref="#/definitions/DhtData")
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "User collection response",
     *         @SWG\Schema(ref = "#/definitions/DhtData")
     *     ),
     * )
     */
    public function actionAdd()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        $dht = new DHtData();
        $dht->Temperature = $data["Temperature"];
        $dht->Humidity = $data["Humidity"];
        if ($data["Created_at"])
            $dht->Created_at = date("Y-m-d H:i:s", strtotime($data["Created_at"]));
        else
            $dht->Created_at = date("Y-m-d H:i:s", time());
        if ($data["Updated_at"])
            $dht->Updated_at = date("Y-m-d H:i:s", strtotime($data["Updated_at"]));
        else
            $dht->Updated_at = date("Y-m-d H:i:s", time());
        $op_result = $dht->save();
        if ($dht->Temperature > CriticalValues::$maxTemperature || $dht->Temperature < CriticalValues::$minTemperature) {
            $client = new Client(new Version2X('https://raspi-info-bot.herokuapp.com'));
            $client->initialize();
            $client->emit('critical', ['param' => 'temperature']);
            $client->close();
        }
        if ($dht->Humidity > CriticalValues::$maxHumidity || $dht->Humidity < CriticalValues::$minHumidity) {
            $client = new Client(new Version2X('https://raspi-info-bot.herokuapp.com'));

            $client->initialize();
            $client->emit('critical', ['param' => 'humidity']);
            $client->close();
        }
        $res["result"] = $op_result;
        $result = Json::encode($res);
        \Yii::$app->response->content = $result;
    }

    /**
     * @SWG\Put(path="/dhts/update/{id}",
     *     tags={"DhtData"},
     *     summary="Update dht data",
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *     @SWG\Parameter(
     *        in = "body",
     *        name = "body",
     *        description = "body",
     *        required = true,
     *         @SWG\Schema(ref="#/definitions/DhtData")
     *     ),
     *     @SWG\Parameter(
     *         ref="#/parameters/entity_id"
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "User collection response",
     *         @SWG\Schema(ref = "#/definitions/DhtData")
     *     ),
     * )
     */
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

    /**
     * @SWG\Delete(path="/dhts/delete/{id}",
     *     tags={"DhtData"},
     *     summary="Delete dht data",
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *     @SWG\Parameter(
     *         ref="#/parameters/entity_id"
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "User collection response",
     *         @SWG\Schema(ref = "#/definitions/DhtData")
     *     ),
     * )
     */
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
    /**
     * @SWG\Post(path="/dhts/search",
     *     tags={"DhtData"},
     *     summary="Search dht data",
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *       @SWG\Parameter(
     *        in = "body",
     *        name = "body",
     *        description = "Search filter",
     *        required = true,
     *         @SWG\Schema(ref="#/definitions/DhtSearch")
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "User collection response",
     *         @SWG\Schema(ref = "#/definitions/DhtData")
     *     ),
     * )
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

    /**
     * @SWG\Post(path="/dhts/get/{id}",
     *     tags={"DhtData"},
     *     summary="Get dht data",
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *     @SWG\Parameter(
     *     in = "path",
     *     name = "id",
     *     description = "Entry id",
     *     required = true,
     *     type="integer"
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "User collection response",
     *         @SWG\Schema(ref = "#/definitions/DhtData")
     *     ),
     * )
     */
    public function actionGet($id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $dht = DhtData::findOne($id);
        $json = JSON::encode($dht);

        \Yii::$app->response->content = $json;
    }

    /**
     * @SWG\Post(path="/dhts/last",
     *     tags={"DhtData"},
     *     summary="Get last dht entry",
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *     @SWG\Response(
     *         response = 200,
     *         description = "User collection response",
     *         @SWG\Schema(ref = "#/definitions/DhtData")
     *     ),
     * )
     */
    public function actionLast()
    {
        if (\Yii::$app->request->isGet) {
            $max = DhtData::find()->max('id');
            $dht = DhtData::findOne($max);

            return $this->render('last', [
                'temp' => $dht->Temperature,
                'hum' => $dht->Humidity,
                'date' => $dht->Created_at
            ]);
        }

        if (\Yii::$app->request->isPost) {
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
    }

    /**
     * @SWG\Post(path="/dhts/first",
     *     tags={"DhtData"},
     *     summary="Get first dht entry",
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *     @SWG\Response(
     *         response = 200,
     *         description = "User collection response",
     *         @SWG\Schema(ref = "#/definitions/DhtData")
     *     ),
     * )
     */
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

    /**
     * @SWG\Post(path="/dhts/firstlastdates",
     *     tags={"DhtData"},
     *     summary="Get corner dates",
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *     @SWG\Response(
     *         response = 200,
     *         description = "User collection response",
     *         @SWG\Schema(ref = "#/definitions/DhtData")
     *     ),
     * )
     */
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

    /**
     * @SWG\Post(path="/dhts/datecount",
     *     tags={"DhtData"},
     *     summary="Get dates count",
     *     produces={"application/json"},
     *     consumes={"application/json"},
     *     @SWG\Response(
     *         response = 200,
     *         description = "User collection response",
     *         @SWG\Schema(ref = "#/definitions/DhtData")
     *     ),
     * )
     */
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
