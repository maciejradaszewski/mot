<?php

/**
 * Class uses to dump data from database
 *
 * @author Patryk Jar <p.jar@kainos.com>
 */
class DbDumper
{
    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Returns SQL statement to populate $table. All data are retrieved from current database.
     * Returns an empty string if table is empty.
     *
     * @param string $table
     *
     * @return string
     */
    public function dumpTable($table)
    {
        $insert = $this->createInsertStubStatement($table);
        $data = $this->getTableData($table);
        $sql = [];

        $n = count($data);

        if (0 === $n) {
            return '';
        }

        while ($n--) {
            $noEmptyStringsRow = array_map(
                function ($item) {
                    return (null === $item) ? 'NULL' : "'" . addslashes($item) . "'";
                },
                $data[$n]
            );
            $sql[] = '(' . implode(',', $noEmptyStringsRow) . ')';
        }

        return $insert . implode(",\n", $sql) . ';';
    }

    /**
     * Returns table structure
     *
     * @param string $table
     *
     * @return array
     */
    private function getTableDescription($table)
    {
        return $this->fetchAll("DESCRIBE {$table}");
    }

    /**
     * Returns all data from table in db as an assoc array
     *
     * @param string $table
     *
     * @return array
     */
    private function getTableData($table)
    {
        return $this->fetchAll("SELECT * FROM {$table}");
    }

    private function fetchAll($query)
    {
        $queryResult = $this->pdo->query($query);

        return $queryResult->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create SQL stub statement "INSERT INTO {$table} ([list of columns]) VALUES\n"
     *
     * @param string $table
     *
     * @return string
     */
    private function createInsertStubStatement($table)
    {
        $structure = $this->getTableDescription($table);
        $insert = 'INSERT INTO `' . $table . '` (';
        foreach ($structure as $column) {
            $insert .= '`' . $column['Field'] . '`, ';
        }

        return substr($insert, 0, -2) . " ) VALUES\n";
    }
}
