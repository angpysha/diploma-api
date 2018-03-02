<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 02.03.2018
 * Time: 22:28
 */

namespace app\modules\v1\models;

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
/**
 * @SWG\Definition(required={"Temperature", "Pressure","Altitude"})
 *
 * @SWG\Property(property="Temperature", type="number")
 * @SWG\Property(property="Pressure", type="number")
 * @SWG\Property(property="Altitude", type="number")
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