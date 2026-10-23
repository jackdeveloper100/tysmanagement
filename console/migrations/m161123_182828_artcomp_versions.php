<?php

use yii\db\Migration;

class m161123_182828_artcomp_versions extends Migration
{
    public function safeUp()
    {
        $this->createTable('artcomp_version', [
            'id' => $this->primaryKey(),
            'artcomp_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),

            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull() . ' ON UPDATE CURRENT_TIMESTAMP'
        ]);

        $this->addForeignKey(
            'fk-artcomp_version-artcomp_id',
            'artcomp_version',
            'artcomp_id',
            'artwork_comp',
            'id',
            'CASCADE'
        );

        $this->createTable('artcomp_version_state', [
            'id' => $this->primaryKey(),
            'artcomp_version_id' => $this->integer()->notNull(),
            'artwork_item_id' => $this->integer()->notNull(),
            'artwork_item_state_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey(
            'fk-artcomp_version_state-artcomp_version_id',
            'artcomp_version_state',
            'artcomp_version_id',
            'artcomp_version',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-artcomp_version_state-artwork_item_id',
            'artcomp_version_state',
            'artwork_item_id',
            'artwork_item',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('artcomp_version_state');
        $this->dropTable('artcomp_version');
    }
}
