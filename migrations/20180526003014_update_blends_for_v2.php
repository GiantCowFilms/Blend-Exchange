<?php
require_once 'MigrationRandomIdTrait.php'; //Sloppy Include

use \Phinx\Migration\AbstractMigration;
/**
 * Update a V1 database to support V2
 */
class UpdateBlendsForV2 extends AbstractMigration
{
    use MigrationRandomIdTrait;
    public function idSize () {
        return 8;
    }

    public function up()  {
        //This will take a while . . . 
        ini_set('max_execution_time', 60 * 30); 

        $table = $this->table('blends',['primary_key' => 'id']);
        $this->execute('ALTER TABLE `blends` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;');
        //Add new Columns
        $addNewIds = !$table->hasColumn('legacy_id');
        if ($addNewIds) {
            $table->renameColumn('id','legacy_id');
            $table->update();
            $table->changeColumn('legacy_id','integer',['identity' => true]);
            $table->addIndex('legacy_id',['unique' => true]);
            $table->addColumn('id','string',['limit' => 8]);
            //Primary column swap moved to end of file to prevent mis-ordering. This is annoying.
        }
        //Remove extraneous Columns 
        if ($table->hasColumn('downloads')) {
            $table->removeColumn('downloads');
        }
        if ($table->hasColumn('downloads')) {
            $table->removeColumn('views');
        }
        if ($table->hasColumn('downloads')) {
            $table->removeColumn('flags');
        }
        $table->changeColumn('password',$table->getColumn('password')->setOptions(['default' => '']));
        $table->changeColumn('adminComment',$table->getColumn('adminComment')->setOptions(['default' => '']));
        $table->changeColumn('valid',$table->getColumn('valid')->setOptions(['default' => 0]));
        $table->changeColumn('deleted',$table->getColumn('deleted')->setOptions(['default' => 0]));
        $table->changeColumn('owner','string',['limit' => 12,'null' => true]);
        $table->changeColumn('fileGoogleId',$table->getColumn('fileGoogleId')->setOptions(['null' => true]));
        $table->changeColumn('fileSize',$table->getColumn('fileSize')->setOptions(['null' => true]));
        $table->changeColumn('fileName',$table->getColumn('fileName')->setOptions(['null' => true]));
        $table->changeColumn('date',$table->getColumn('date')->setOptions(['default' => 'CURRENT_TIMESTAMP']));
        $table->changeColumn('uploaderIp',$table->getColumn('uploaderIp')->setOptions(['limit' => 512]));
        $table->update();

        //This is one WIERD migration.
        if($addNewIds) {
            $i = 0;
            while (true) {
                $stmt = $this->query(sprintf('SELECT * FROM `blends` ORDER BY `date` LIMIT %u, %u',$i,$i + 100)); // returns PDOStatement
                $rows = $stmt->fetchAll();
                if (count($rows) === 0) {
                    echo 'finished update.'.PHP_EOL;
                    break;
                }
                $query = '';
                foreach ($rows as $row) {
                    $query .= 'UPDATE `blends` SET `id`=\''.$this->getRandomId().'\' WHERE `legacy_id`=' .$row["legacy_id"]."". "; ";
                }
                $this->execute('START TRANSACTION; ' . $query . ' COMMIT;');
                echo sprintf('Updated ids for rows %u through %u',$i,$i + 100) . PHP_EOL;
                $i += 100;
            }

            //Only way I could find to swap the primary key to the new ID column
            $this->execute('ALTER TABLE `blends` DROP PRIMARY KEY, ADD PRIMARY KEY(`id`)');
        }
    }
    public function down() {
        $table = $this->table('blends');
        $table->changeColumn('owner','integer',['null' => false]);
        $table->changeColumn('fileGoogleId',$table->getColumn('fileGoogleId')->setOptions(['null' => false]));
        if ($table->hasColumn('legacy_id')) {
            $table->removeColumn('id');
            $table->renameColumn('legacy_id','id');
            $table->removeIndex(['legacy_id']);
            $table->update();
            //Only way I could find to swap the primary key to the new ID column
            $this->execute('ALTER TABLE `blends` ADD PRIMARY KEY(`id`)');
        }
    }
}
