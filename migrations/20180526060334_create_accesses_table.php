<?php


use \Phinx\Migration\AbstractMigration;

class CreateAccessesTable extends AbstractMigration
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
        $exists = $this->hasTable('accesses');
        $table = $this->table('accesses');
        if (!$exists) {
            $table
             //(Added by default) ->addColumn('id','integer',['signed' => false])
             ->addColumn('accept','integer')
             ->addColumn('fileId','integer',['signed' => false])
             ->addColumn('type','string',['limit' => 200])
             ->addColumn('ref','string',['limit' => 200])
             ->addColumn('ip','string',['limit' => 50])
             ->addColumn('val','string',['limit' => 256])
             ->addColumn('date','datetime')
             ->create();
        }
    }
}
