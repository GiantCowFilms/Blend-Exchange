<?php


use \Phinx\Migration\AbstractMigration as Migration;

class UpdateAccessesIdsForV2 extends Migration
{
    use MigrationRandomIdTrait;
    public function idSize () {
        return 12;
    }
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
    public function up()
    {
        $path = __DIR__.'/../storage/migration';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }   
        $logFile = fopen($path . '/' . basename(__FILE__, '.php') . '_progress.log','a+');
        $cursor = -3;

        fseek($logFile, $cursor, SEEK_END);
        $char = fgetc($logFile);
        $line  = '';

        while ($char !== false && $char !== "\n" && $char !== "\r") {
            /**
             * Prepend the new char
             */
            $line = $char . $line;
            fseek($logFile, --$cursor, SEEK_END);
            $char = fgetc($logFile);
        }
        $last = substr($line,strrpos($line," ") + 1);
        $start = intval($last);

        $table = $this->table('accesses');
        //$i = 0;
        $i = $start;
        while (true) {
            $stmt = $this->query(sprintf('SELECT * FROM `accesses` LIMIT %u, %u',$i,$i + 100)); // returns PDOStatement
            $rows = $stmt->fetchAll();
            if (count($rows) === 0) {
                break;
            }
            $query = '';
            foreach ($rows as $row) {
                $query .= 'UPDATE `accesses` SET `id`=\''.$this->getRandomId().'\' WHERE `id`=\''.$row["id"]."'" . "; ";
            }
            $this->execute('START TRANSACTION; ' . $query . ' COMMIT;');
            $progress = sprintf('Updated ids for rows %u through %u',$i,$i + 100) . PHP_EOL;
            echo $progress;
            fwrite($logFile,$progress);
            $i += 100;
        }
    }
}
