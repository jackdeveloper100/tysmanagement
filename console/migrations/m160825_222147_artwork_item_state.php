<?php

use yii\db\Migration;

class m160825_222147_artwork_item_state extends Migration
{
    public function up()
    {
        $this->createTable('artwork_item_state', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->notNull(),
            'file_name' => $this->string()->unique()->notNull(),
            'original_file_name' => $this->string()->notNull(),

            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull() . ' ON UPDATE CURRENT_TIMESTAMP'
        ]);


        $this->addForeignKey(
            'fk-artwork_item-item_id',
            'artwork_item_state',
            'item_id',
            'artwork_item',
            'id'
        );
    }

    public function down()
    {
        $this->dropTable('artwork_item_state');
    }
}
