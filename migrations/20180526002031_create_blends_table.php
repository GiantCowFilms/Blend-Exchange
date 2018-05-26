<?php


use \Phinx\Migration\AbstractMigration;

class CreateBlendsTable extends AbstractMigration
{
    public function change()  {
        $exists = $this->hasTable('blends');
        $table = $this->table('blends');
        if (!$exists) {
            $table
             //(Added by default) ->addColumn('id','integer',['signed' => false])
             ->addColumn('fileName','string',['limit' => 556])
             ->addColumn('fileGoogleId','string',['limit' => 556])
             ->addColumn('uploaderIp','string',['limit' => 50])
             ->addColumn('questionLink','string',['limit' => 255])
             ->addColumn('password','string',['limit' => 255])
             ->addColumn('adminComment','string',['limit' => 1024])
             ->addColumn('valid','integer',[])
             ->addColumn('owner','integer',['signed' => false])
             ->addColumn('fileSize','integer',['signed' => false])
             ->addColumn('date','datetime')
             ->addColumn('deleted','integer')->create();
        }
    }
}
