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
        $data = Sensor::find()->where(['name' =>$inputdata["type"]])->offset($inputdata["skip"])->limit($inputdata["take"])->all();
        $json = JSON::encode($data);
        \Yii::$app->response->content = $json;
    }

    public function actionUpdate($id) 
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $inputdata = Json::decode(\Yii::$app->request->getRawBody());

        $data = Sensor::findOne($id);
        $data["name"] = $inputdata["type"];
        $data["data"] = $inputdata["data"];
        $data->save();
        var_dump($parama);
    }

    public function actionDelete($id) 
    {
        $data = Sensor::findOne($id);
        $data->delete();
    }

}