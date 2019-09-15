<?
namespace app\modules\v1\models;
use yii\mongodb\ActiveRecord;
class Sensor extends ActiveRecord {

    public static function collectionName()
    {
        return 'sensordata';
    }

    public function attributes()
    {
        return ['_id', 'name', 'data'];
    }
}