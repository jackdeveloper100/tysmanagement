<?php

use yii\db\Migration;

class m160913_001349_favorites extends Migration
{
    public function up()
    {
        $this->createTable('favorites', [
            'user_id' => $this->integer()->notNull(),
            'job_id' => $this->integer()->notNull(),
            'PRIMARY KEY(user_id, job_id)'
        ]);

        $this->addForeignKey(
            'fk-favorites-user_id',
            'favorites',
            'user_id',
            'user',
            'id'
        );
        $this->addForeignKey(
            'fk-favorites-job_id',
            'favorites',
            'job_id',
            'job',
            'id'
        );
    }

    public function down()
    {
        $this->dropTable('favorites');
    }
}
