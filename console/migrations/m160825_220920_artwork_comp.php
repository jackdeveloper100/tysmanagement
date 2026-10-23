<?php

use yii\db\Migration;

class m160825_220920_artwork_comp extends Migration
{
    public function up()
    {
        $this->createTable('artwork_comp', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'status' => $this->smallInteger(1)->defaultValue(1),

            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull() . ' ON UPDATE CURRENT_TIMESTAMP'
        ]);

        $this->addForeignKey(
            'fk-artwork_comp-project_id',
            'artwork_comp',
            'project_id',
            'job',
            'id'
        );
    }

    public function down()
    {
        $this->dropTable('artwork_comp');
    }
}
