<?php

use \Phinx\Migration\AbstractMigration;
class CreateUsersTable extends AbstractMigration
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
        $table = $this->table('users',['id' => false, 'primary_key' => 'id']);
        $table->addColumn('id','string',['limit' => 10]);
        $table->addColumn('stackId','string',['limit' => 50]); //Technically an integer
        $table->addColumn('stackToken','string',['limit' => 255,'null' => true]);
        $table->addColumn('role','integer',['default' => 0]);
        $table->addColumn('email','string',['limit' => 254,'null' => true]);
        $table->addColumn('username','string',['limit' => 255]);
        $table->addColumn('password','string',['null' => true,'limit' => 1024]); //Additional Security Layer for Admins
        //eloquent timestamps
        $table->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('deleted_at', 'timestamp', ['default' => null,'null' => true]);
        $table->create();
    }
}
