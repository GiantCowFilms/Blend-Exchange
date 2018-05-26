<?php


use \Phinx\Migration\AbstractMigration as Migration;

class UpdateAccessesIdsForV2 extends Migration
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
    public function up()
    {
        $table = $this->table('accesses');
        $i = 0;
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
            echo sprintf('Updated ids for rows %u through %u',$i,$i + 100) . PHP_EOL;
            $i += 100;
        }
    }
}
