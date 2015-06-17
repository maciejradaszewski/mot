<?php

/**
 * Class used to generate sql scripts in `populate/*` folders
 *
 * It assumes there is folders' structure like this one:
 *
 * mot-api/
 *      db/dev/
 *          bin/
 *              lib/
 *              template/
 *                  populate-table.tsql <- this file MUST be found here
 *              dump_db.php
 *          populate/
 *              static-data/        <- scripts in this folder will be changed
 *              static-data-mot2/   <- scripts in this folder will be changed
 *              test-data/          <- scripts in this folder will be changed
 */
class SqlScriptSaver
{
    /**
     * @var DbDumper
     */
    private $dbDumper;

    /**
     * Directory for all scripts
     *
     * @var string
     */
    private $dir;

    /**
     * Path to template file
     *
     * @var string
     */
    private $templateFilePath;

    public function __construct(DbDumper $dbDumper, $dir, $cleanDirectoryBefore = true)
    {
        $this->dir = $dir;
        $this->templateFilePath = __DIR__ . '/../template/populate-table.tsql';

        if (true === $cleanDirectoryBefore) {
            self::removeAllFromDirectory();
        }

        $this->dbDumper = $dbDumper;
    }

    /**
     * Creates sql script to populate database. Returns number of dumped tables.
     *
     * @use $this->templateFilePath file with template for sql script
     *
     * @param array $listOfTables
     *
     * @return int - number of dumped tables
     */
    public function run($listOfTables)
    {
        $tableCount = 0;
        $template = file_get_contents($this->templateFilePath);

        foreach ($listOfTables as $table) {
            $insert = $this->dbDumper->dumpTable($table);

            if ('' !== $insert) {
                $templateApplied = str_replace('%table%', $table, $template);
                $templateApplied = str_replace('%insert%', $insert, $templateApplied);
                file_put_contents($this->dir . '/' . $table . '.sql', $templateApplied);
                $tableCount++;
            }
        }

        return $tableCount;
    }

    /**
     * Removes __ALL__ *.sql scripts from given directory
     *
     * @throws \LogicException
     */
    private function removeAllFromDirectory()
    {
        if (file_exists($this->dir) && is_dir($this->dir)) {
            array_map('unlink', glob("{$this->dir}/*.sql"));
        } else {
            throw new \LogicException($this->dir . ' does not exist, or is not a folder');
        }
    }
}
