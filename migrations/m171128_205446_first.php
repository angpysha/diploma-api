<?php

use yii\db\Migration;

/**
 * Class m171128_205446_first
 */
class m171128_205446_first extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // public $id;
        // public $Updated_at;
        // public $Created_at;
        // public $Temperature;
        // public $Humidity;
            $this->createTable('DhtData',[
                'id'=> $this->primaryKey(),
                'Temperature' => $this->float(),
                'Humidity' => $this->float(),
                'Updated_at' => $this->dateTime(),
                'Created_at' => $this->dateTime()
            ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171128_205446_first cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_205446_first cannot be reverted.\n";

        return false;
    }
    */
}
