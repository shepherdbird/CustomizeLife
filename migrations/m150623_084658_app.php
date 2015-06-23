<?php

use yii\db\Schema;
use yii\db\Migration;

class m150623_084658_app extends Migration
{
    public function safeUp()
    {
    	$this->createTable('app',[
    		'id' => Schema::TYPE_PK,
    		'name' => Schema::TYPE_STRING . ' NOT NULL',
    		'version' => Schema::TYPE_STRING . ' NOT NULL',
    		'url' => Schema::TYPE_STRING . ' NOT NULL',
    		'stars' => Schema::TYPE_BIGINT . ' NOT NULL DEFAULT 0',
    		'downloadcount' => Schema::TYPE_BIGINT . ' NOT NULL DEFAULT 0',
    	],'ENGINE=InnoDB');

    }

    public function safeDown()
    {
    	//$this->dropTable('appofkind');
    	//$this->dropTable('appcomments');
    	//$this->dropTable('collect_person');
        $this->dropTable('app');
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
