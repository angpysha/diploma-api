<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Bmp180".
 *
 * @property integer $id
 * @property double $Temperature
 * @property double $Pressure
 * @property double $Altitude
 * @property string $Updated_at
 * @property string $Created_at
 */
class Bmp180 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Bmp180';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Temperature', 'Pressure', 'Altitude'], 'number'],
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
            'Pressure' => 'Pressure',
            'Altitude' => 'Altitude',
            'Updated_at' => 'Updated_at',
            'Created_at' => 'Created_at',
        ];
    }
}
