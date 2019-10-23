<?
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 02.03.2018
 * Time: 22:21
 */

namespace app\modules\v1\controllers;


use yii\web\Controller;
use app\modules\v1\models\Sensor;
use yii\helpers\Json;
use yii\web\Response;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use yii\mongodb\Query;
use yii\mongodb\Collection;
use Yii;

class SensorController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionAdd() 
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(\Yii::$app->request->getRawBody());
        $count = count(array_keys($data));
        $indexes = array_keys($data);

        $data1 = array();
        for ($i = 0;$i < $count; $i++) {
            $data1[$indexes[$i]] = $data[$indexes[$i]];

        }
        $datetime = new \DateTime();
        $datetime->add(new \DateInterval("PT3H"));
        $data1["date"] = $datetime->format('Y-m-d H:i:s');
        $collection = Yii::$app->mongodb->getCollection('sensordata');
        $collection->insert($data1);
    }

    public function actionTest() 
    {
        var_dump("testtt");
    }

    public function actionGetall() 
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $collection = Yii::$app->mongodb->getCollection('sensordata');
        $data = $collection->find([]);
        $data = $data;
        $json = JSON::encode($data);
        $json= $json;
        \Yii::$app->response->content = $json;
    }

    public function actionGetbytype() 
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $inputdata = Json::decode(\Yii::$app->request->getRawBody());
        $query = new Query();
        $data = $query->select([])->from('sensordata')->where(['type' => $inputdata["type"]])->offset($inputdata["skip"])->limit($inputdata["take"])->all();
        $json = JSON::encode($data);
        // $collection = Yii::$app->mongodb->getCollection('sensordata');
     //   $inputdata = Json::decode(\Yii::$app->request->getRawBody());
        // $data = Sensor::find()->where(['name' =>$inputdata["type"]])->offset($inputdata["skip"])->limit($inputdata["take"])->all();
        // $json = JSON::encode($data);
        \Yii::$app->response->content = $json;
    }

    public function actionUpdate($id) 
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        $inputdata = Json::decode(\Yii::$app->request->getRawBody());
       
        $collection = Yii::$app->mongodb->getCollection('sensordata');
        $count = count(array_keys($inputdata));
        $indexes = array_keys($inputdata);
        $data = array();
        for ($i = 0;$i < $count; $i++) {
            $data[$indexes[$i]] = $inputdata[$indexes[$i]];

        }

        $collection->update(['_id' => $id],$data);
        // $data = Sensor::findOne($id);
        // $data["name"] = $inputdata["type"];
        // $data["data"] = $inputdata["data"];
        // $data->save();
        // var_dump($parama);
    }

    public function actionDelete($id) 
    {
       $collection = Yii::$app->mongodb->getCollection('sensordata');
       //$query = new Query();
       //$data = $query->select([])->from('sensordata')->where(['_id' => $id])->limit(1)->all();
       $data = $collection->findOne(['_id' => $id]);
       $collection->remove($data);

     // $json = JSON::encode($data);
     // \Yii::$app->response->content = $json;

      //  $data = Sensor::findOne($id);
      //  $data->delete();
    }

    /**
     * @SWG\Post(path="/web/api/v1/sensors/get/{id}",
     *     tags={"Sensor"},
     *     summary="Get sensor data",
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
     *         @SWG\Schema(ref = "#/definitions/Sensor")
     *     ),
     * )
     */
    public function actionGet($id) {
        $collection = Yii::$app->mongodb->getCollection('sensordata');
        //$query = new Query();
        //$data = $query->select([])->from('sensordata')->where(['_id' => $id])->limit(1)->all();
        $data = $collection->findOne(['_id' => $id]);
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $json = JSON::encode($data);
        \Yii::$app->response->content = $json;
    }

    public function actionSearch() {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $inputdata = Json::decode(\Yii::$app->request->getRawBody());
        $query = new Query();
        $date1 = $inputdata["datefrom"];
        $date2 = $inputdata["dateto"];

        $data = $query->select([])->from('sensordata');

        if ($inputdata["type"])
        {
            $data = $data->andWhere(["type" => $inputdata["type"]]);
        }
        
        if ($date1) {
            $data = $data->andWhere([">=","date",$date1]);
        }

        if ($date2) {
            $data = $data->andWhere(["<=","date",$date2]);
        }
        $data = $data->all();
        $json = JSON::encode($data);
        \Yii::$app->response->content = $json;


        
    }

    public function actionGetByDate() {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        $inputdata = Json::decode(\Yii::$app->request->getRawBody());


    }

    public function actionGetlast() {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $collection = Yii::$app->mongodb->getCollection('sensordata');
        $data = $collection->distinct("type");
       
       // var_dump($data);
        $returnData = array();

        foreach ($data as $item) {
            $query = new Query();
            $sdata = $query->select([])->from('sensordata')->where(['type' => $item])->orderBy(['_id' => SORT_DESC])->one();
           // var_dump($sdata);
            $returnData[$item] = $sdata;
           // $sensorItem = $collection->
        }
      //  var_dump($returnData);
        $json = JSON::encode($returnData);
        \Yii::$app->response->content = $json;
    }

    public function actionGetconnectedsensors() {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $collection = Yii::$app->mongodb->getCollection('sensordata');
        $data = $collection->distinct("type");

        $json = JSON::encode($data);
        \Yii::$app->response->content = $json;
    }


}