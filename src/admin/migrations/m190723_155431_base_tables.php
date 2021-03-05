<?php

use yii\db\Migration;

/**
 * Class m190723_155431_base_tables
 */
class m190723_155431_base_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%calendar_item}}', [
            'id' => $this->primaryKey(),
            'person_id' => $this->integer()->notNull(),
            'start_date' => $this->integer()->notNull(),
            'end_date' => $this->integer()->notNull(),
            'title' => $this->string(),
            'comment' => $this->text(),
            'email' => $this->string(),
            'phone' => $this->string(),
            'is_fix' => $this->boolean()->defaultValue(true),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createIndex('person_id', '{{%calendar_item}}', ['person_id']);
        $this->createIndex('start_date', '{{%calendar_item}}', ['start_date']);
        $this->createIndex('end_date', '{{%calendar_item}}', ['end_date']);

        $this->createTable('{{%calendar_person}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'color' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%calendar_item}}');

        $this->dropTable('{{%calendar_person}}');
    }
}
