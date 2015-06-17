<?php

namespace EnumGeneration;

use InvalidArgumentException;
use PDO;
use PDOException;

/**
 * Helper used by mot-common-web-module/generate_enums.php to process the generation of enum classes.
 */
class EnumGenerationHelper
{
    /** @var string $directoryPath */
    private $directoryPath;
    /** @var PDO */
    private $databaseHandler;

    /**
     * @param string $directoryPath Where the enums files should be generated
     */
    public function setDirectoryPath($directoryPath)
    {
        $this->directoryPath = $directoryPath;
    }

    public function createDirectoryIfNotExisting()
    {
        if (!file_exists($this->directoryPath)) {
            mkdir($this->directoryPath, 0777, true);
        }
    }

    public function removeAllPreviouslyGeneratedEnums()
    {
        array_map('unlink', glob($this->directoryPath . '/*.php'));
    }

    /**
     * Connects to the database for retrieval of data, or if it fails it logs a message and aborts the script
     *
     * @param string $dbName
     * @param string $host
     * @param string $username
     * @param string $password
     */
    public function setupDatabaseConnection($dbName, $host, $username, $password)
    {
        try {
            $this->databaseHandler = new PDO("mysql:dbname={$dbName};host={$host}", $username, $password);
        } catch (PDOException $e) {
            echo sprintf(
                "Error - Problem accessing the database. Enum generation aborted.\n" .
                "ExceptionMessage: %s\n",
                $e->getMessage()
            );
            exit(1);
        }
    }

    /**
     * @param EnumGenerationBlueprint[] $inputArray
     */
    public function generateEnumClasses($inputArray)
    {
        array_walk($inputArray, [$this, 'generateEnum']);
        echo "Generations of enums complete\n";
    }

    /**
     * @param EnumGenerationBlueprint $blueprint
     */
    private function generateEnum(EnumGenerationBlueprint $blueprint)
    {
        try {
            $filePath = $blueprint->createFilePathString($this->directoryPath);
            $fileContents = $this->generateFileContents($blueprint);

            file_put_contents($filePath, $fileContents);

        } catch (InvalidArgumentException $e) {
            echo sprintf("%s Generation of %s skipped\n", $e->getMessage(), $blueprint->getEnumName());
        }
    }

    /**
     * @param EnumGenerationBlueprint $blueprint
     *
     * @return string
     */
    private function generateFileContents(EnumGenerationBlueprint $blueprint)
    {
        $rows = $this->getAllRowsFromTable($blueprint->getTableName());

        return $blueprint->convertRowsToEnumFileContents($rows);
    }

    private function getAllRowsFromTable($table)
    {
        $selectResultSet = $this->databaseHandler->query("select * from {$table}");
        if (false === $selectResultSet) {
            throw new InvalidArgumentException("Error - Problem accessing table with name '{$table}'.");
        }

        return $selectResultSet->fetchAll();
    }
}
