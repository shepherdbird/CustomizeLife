<?php

use yii\db\Schema;
use yii\db\Migration;

class m150623_074316_friends extends Migration
{
    public function safeUp()
    {
    	$this->execute("DROP TABLE IF EXISTS friends");
    	$this->createTable('friends', [
    			'id' => Schema::TYPE_PK,
    			'myid' => Schema::TYPE_INTEGER . ' NOT NULL',
    			'friendid' => Schema::TYPE_INTEGER . ' NOT NULL',
    	],'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');
    	$this->createIndex('friends', 'friends', 'myid');
    	$this->addForeignKey('myidKey', 'friends', 'myid', 'user', 'id','CASCADE','CASCADE');
    	$this->addForeignKey('friendidKey', 'friends', 'friendid', 'user', 'id','CASCADE','CASCADE');
    }

    public function safeDown()
    {
    	$this->dropForeignKey('myidKey', 'friends');
    	$this->dropForeignKey('friendidKey', 'friends');
        $this->dropTable('friends');
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
