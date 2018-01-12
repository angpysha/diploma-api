<?php

use yii\db\Migration;

/**
 * Class m180112_180846_bmp180
 */
class m180112_180846_bmp180 extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
            $this->createTable("Bmp180",[
                'id'=> $this->primaryKey(),
                'Temperature' => $this->float(),
                'Pressure' => $this->float(),
                'Altitude' => $this->float(),
                'Updated_at' => $this->dateTime(),
                'Created_at' => $this->dateTime()
            ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180112_180846_bmp180 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180112_180846_bmp180 cannot be reverted.\n";

        return false;
    }
    */
}
