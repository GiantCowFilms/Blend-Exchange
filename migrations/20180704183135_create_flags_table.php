<?php


use \Phinx\Migration\AbstractMigration as Migration;

class CreateFlagsTable extends Migration
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
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('flags',['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id','string',['limit' => 12]);
        $table->addColumn('offense','string',['limit' => 50]);
        $table->addColumn('message','string',['limit' => 1024]);
        $table->addColumn('adminMessage','string',['limit' => 1024, 'null' => true]);
        $table->addColumn('ip','string',['limit' => 512]);
        $table->addColumn('fileId','string',['limit' => 8]);
        $table->addColumn('accepted','integer',['default' => 0 ]);
        $table->addColumn('date','datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->create();
    }
}
