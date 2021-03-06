<?php

use yii\db\Schema;
use yii\db\Migration;

class m150623_125822_appofkind extends Migration
{
    public function safeUp()
    {
    	$this->execute("DROP TABLE IF EXISTS appofkind");
    	$this->createTable('appofkind', [
    			'id' => Schema::TYPE_PK,
    			'appid' => Schema::TYPE_INTEGER . ' NOT NULL',
    			'kind' => Schema::TYPE_INTEGER . ' NOT NULL',
    			'status'=>Schema::TYPE_BIGINT . ' DEFAULT 1',
    	],'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB');
    	$this->addForeignKey('appofkindKey', 'appofkind', 'appid', 'app', 'id','CASCADE','CASCADE');
    	$this->addForeignKey('appofkindid', 'appofkind', 'kind', 'tag', 'id','CASCADE','CASCADE');
    }

    public function safeDown()
    {
        //echo "m150623_125822_appofkind cannot be reverted.\n";
        $this->dropForeignKey('appofkindKey', 'appofkind');
        $this->dropForeignKey('appofkindid', 'appofkind');
		$this->dropTable('appofkind');
        //return false;
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
