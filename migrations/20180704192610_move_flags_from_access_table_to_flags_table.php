<?php


use \Phinx\Migration\AbstractMigration as Migration;

class MoveFlagsFromAccessTableToFlagsTable extends Migration
{
    public function up()  {
        $this->execute('INSERT INTO `flags` (`id`,`offense`,`message`,`ip`,`fileId`,`date`) SELECT `id`,SUBSTRING(`val`,0,50),`val`,`ip`,`fileId`,`date` FROM `accesses` WHERE `type`=\'flag\'');
        $this->execute('DELETE FROM `accesses` WHERE `type`=\'flag\'');
    }
    public function down() {
        $this->execute('INSERT INTO `accesses` (`id`,`val`,`message`,`ip`,`fileId`,`date`,`type`) SELECT `id`,`offense`,`message`,`ip`,`fileId`,`date`,"flag" FROM `flags`');
        //Could result in the removal of fields that were not migrated
        //$this->execute('DELETE FROM `flags`');
    }
}
