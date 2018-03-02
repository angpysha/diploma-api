<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "DhtData".
 *
 * @property integer $id
 * @property string $Temperature
 * @property string $Humanity
 * @property string $Updated_at
 * @property string $Created_at
 */
/**
 * @SWG\Definition(required={"Temperature", "Humidity"})
 *
 * @SWG\Property(property="Temperature", type="number")
 * @SWG\Property(property="Humidity", type="number")
 */
class DhtData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DhtData';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Temperature', 'Humidity'], 'number'],
             [['Updated_at', 'Created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'Temperature' => 'Temperature',
            'Humidity' => 'Humidity',
            'Updated_at' => 'Updated_at',
            'Created_at' => 'Created_at'
        ];
    }
}
