<?php

use \Phinx\Migration\AbstractMigration;

class UpdateBlendsCacheAccessCounts extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */

    public function up() {
        $table = $this->table('blends');
        $this->execute("START TRANSACTION");
        $table->addColumn('view_count_cache','integer');
        $table->addColumn('download_count_cache','integer');
        $table->addIndex('view_count_cache');
        $table->addIndex('download_count_cache');
        $table->save();
        $this->execute("
            UPDATE `blends` SET `blends`.`view_count_cache` = IFNULL((SELECT COUNT(DISTINCT `ip`) FROM `accesses` WHERE `type`='view' AND `fileId`=`blends`.`id` GROUP BY `blends`.`id`),0)
        ");
        $this->execute("
            UPDATE `blends` SET `blends`.`download_count_cache` = IFNULL((SELECT COUNT(DISTINCT `ip`) FROM `accesses` WHERE `type`='download' AND `fileId`=`blends`.`id` GROUP BY `blends`.`id`),0)
        ");
        $this->execute("COMMIT");
    }

    public function down () {
        $table = $this->table('blends');
        $table->removeColumn('view_count_cache');
        $table->removeColumn('download_count_cache');
        $table->save();
    }
}
