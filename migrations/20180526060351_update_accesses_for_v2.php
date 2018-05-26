<?php
require_once 'MigrationRandomIdTrait.php'; //Sloppy Include

use \Phinx\Migration\AbstractMigration;
/**
 * Update a V1 database to support V2
 */
class UpdateAccessesForV2 extends AbstractMigration
{
    use MigrationRandomIdTrait;
    public function idSize () {
        return 12;
    }
    public function up()
    {
        //This will take a while . . . 
        ini_set('max_execution_time', 60 * 120); 
        $table = $this->table('accesses');

        $needsUpdate = true; //No migration check for now
        $partial = false;
        if (!$partial) {
            $this->execute('ALTER TABLE `accesses` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;');
           // $this->execute('SET SESSION max_allowed_packet = 128000000');
           // $this->execute('SET SESSION wait_timeout = 128000000');
            if (!$table->hasColumn('message')) {
                $table->addColumn('message','string',['limit' => 1024,'null'=>true, 'default' => '']);
            }
            $table->changeColumn('date',$table->getColumn('date')->setOptions(['default' => 'CURRENT_TIMESTAMP']));
            if ($table->hasColumn('ref')) {
                $table->removeColumn('ref');
            }
            $table->changeColumn('ip',$table->getColumn('ip')->setOptions(['limit' => 512]));
            $table->changeColumn('fileId','string', ['limit' => 8]);
            $table->changeColumn('id','string', ['limit' => 12]);
            $table->update();
            if ($needsUpdate) {
                $this->execute('UPDATE `accesses` INNER JOIN `blends` ON `blends`.`legacy_id` = `accesses`.`fileId` SET `accesses`.`fileId` = `blends`.`id`');
            }
            $table->changeColumn('accept',$table->getColumn('accept')->setOptions(['default' => 0]));
            $table->changeColumn('val',$table->getColumn('val')->setOptions(['default' => '']));
            $table->addColumn('owner','string',['null' => true, 'default' => '', 'limit' => 10]);
            $table->update();
        }
    }

    public function down() {
        // $this->execute('CREATE TABLE `accesses_migrate` SELECT * FROM `accesses`');
        // $this->execute('TRUNCATE TABLE `accesses_migrate`');
        // $this->execute('ALTER TABLE `accesses_migrate` CHANGE `id` `id` INT(11) unsigned NOT NULL');
        // $this->execute('INSERT INTO `accesses_migrate` SELECT * FROM `accesses`');
        // $this->execute('DROP TABLE `accesses`');
        // $this->execute('RENAME TABLE `accesses_migrate` TO `accesses`');
        $table = $this->table('accesses');
        $this->execute('ALTER TABLE `accesses` DROP PRIMARY KEY, ADD `intId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
        $table->update();
        $table->removeColumn('id');
        $table->renameColumn('intId','id');
        $table->update();

        $this->execute('UPDATE `accesses` INNER JOIN `blends` ON `blends`.`id` = `accesses`.`fileId` SET `accesses`.`fileId` = `blends`.`legacy_id`');
        $table->update();
        $table->removeColumn('message');
        $table->removeColumn('owner');
        $table->changeColumn('fileId','integer', ['signed' => false]);
        $table->update();
    }
}
