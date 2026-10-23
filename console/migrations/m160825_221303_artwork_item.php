<?php

use yii\db\Migration;

class m160825_221303_artwork_item extends Migration
{
    public function up()
    {
        $this->createTable('artwork_item', [
            'id' => $this->primaryKey(),
            'comp_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'file_name' => $this->string()->notNull(),

            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull() . ' ON UPDATE CURRENT_TIMESTAMP'
        ]);


        $this->addForeignKey(
            'fk-artwork_item-comp_id',
            'artwork_item',
            'comp_id',
            'artwork_comp',
            'id'
        );
    }

    public function down()
    {
        $this->dropTable('artwork_item');
    }
}
