<?php

namespace EnumGeneration;

use InvalidArgumentException;

/**
 * A class containing instructions used to generate enum classes automatically in the generate_enum.php script
 */
class EnumGenerationBlueprint
{
    /** @var string $enumName */
    private $enumName;
    /** @var string $tableName */
    private $tableName;
    /** @var string $columnNameForEnumKey */
    protected $columnNameForEnumKey;
    /** @var string $columnNameForEnumValue */
    protected $columnNameForEnumValue;
    /** @var string $enumKeyPrefix */
    private $enumKeyPrefix;

    /**
     * @param string $enumName
     * @param string $tableName
     * @param string $columnNameForEnumKey
     * @param string $columnNameForEnumValue
     * @param string $enumKeyPrefix Prefixed to the constant names,
     *                              used in cases like BrakeTestType where name values are ints
     */
    public function __construct(
        $enumName,
        $tableName,
        $columnNameForEnumKey,
        $columnNameForEnumValue,
        $enumKeyPrefix = ''
    ) {
        $this->enumName = $enumName;
        $this->tableName = $tableName;
        $this->columnNameForEnumKey = $columnNameForEnumKey;
        $this->columnNameForEnumValue = $columnNameForEnumValue;
        $this->enumKeyPrefix = $enumKeyPrefix;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @return string
     */
    public function getEnumName()
    {
        return $this->enumName;
    }

    /**
     * @param string $directoryPath parent directory path
     *
     * @return string the full path and file name to be generated
     */
    public function createFilePathString($directoryPath)
    {
        return $directoryPath . '/' . $this->enumName . '.php';
    }

    /**
     * @param array $rows Key/value pairs of column_name to row_value
     *
     * @return string The entire contents of the enum file to be generated
     */
    public function convertRowsToEnumFileContents($rows)
    {
        $this->validateThatRequestedRowsExist($rows);

        $constantDefinitionsBlock = $this->mapAllRowsToConstantDefinitionBlock($rows);
        $constantsArrayBlock = $this->mapAllRowsToConstantsArrayBlock($rows);

        $fileContents = $this->fillTemplate($constantDefinitionsBlock, $constantsArrayBlock);

        return $fileContents;
    }

    /**
     * @param array $row Key/value pairs of column_name to row_value
     *
     * @return string Definition of constant in format "const CONSTANT_NAME = 'constant_value';"
     */
    protected function mapRowToConstantDefinition($row)
    {
        return sprintf(
            "const %s = '%s';",
            $this->textToConstant($row[$this->columnNameForEnumKey]),
            $row[$this->columnNameForEnumValue]
        );
    }

    /**
     * @param string $text
     *
     * @return string Converted to uppercase with underscores as separators
     */
    protected function textToConstant($text)
    {
        return $this->enumKeyPrefix . strtr(
            strtoupper($text),
            ['('   => '',
             ')'   => '',
             ' '   => '_',
             '-'   => '_',
             '/'   => '_',
             ','   => '',
             ':'   => '',
             ' - ' => '_',
             '+'   => 'PLUS'
            ]
        );
    }

    /**
     * @param array $rows Key/value pairs of column_name to row_value
     */
    private function validateThatRequestedRowsExist($rows)
    {
        $keysToCheck = [$this->columnNameForEnumKey, $this->columnNameForEnumValue];
        array_walk(
            $keysToCheck,
            function ($key) use ($rows) {
                if (!array_key_exists($key, $rows[0])) {
                    throw new InvalidArgumentException(
                        "Error - Problem accessing row '$key' in the '$this->tableName' table."
                    );
                }
            }
        );
    }

    /**
     * @param array $rows Key/value pairs of column_name to row_value
     *
     * @return string A multi-line, correctly-indented string with all the const definitions
     */
    private function mapAllRowsToConstantDefinitionBlock($rows)
    {
        $constantDefinitions = array_map([$this, 'mapRowToConstantDefinition'], $rows);

        return implode("\n    ", $constantDefinitions);
    }

    /**
     * @param array $rows Key/value pairs of column_name to row_value
     *
     * @return string A multi-line, correctly-indented string of all the constants in an array
     */
    private function mapAllRowsToConstantsArrayBlock($rows)
    {
        $this->mapRowToConstantDefinition($rows[0]);
        $constantNames = array_map([$this, 'mapRowToConstantReference'], $rows);

        return "[\n            " . implode("\n            ", $constantNames) . "\n        ]";
    }

    /**
     * @param array $row Key/value pairs of column_name to row_value
     *
     * @return string Reference of constant in format "self::CONSTANT_NAME,"
     */
    private function mapRowToConstantReference($row)
    {
        return sprintf(
            'self::%s,',
            $this->textToConstant($row[$this->columnNameForEnumKey])
        );
    }

    /**
     * @param string $constantDefinitions Constant definition block
     * @param string $constantsArray Array of the constants block
     *
     * @return string The complete generated contents to be written to the enum file
     */
    private function fillTemplate($constantDefinitions, $constantsArray)
    {
        return <<<CODE
<?php

namespace DvsaCommon\Enum;

/**
 * Enum class generated from the '{$this->tableName}' table
 *
 * DO NOT EDIT! -- THIS CLASS IS GENERATED BY mot-common-web-module/generate_enums.php
 */
class {$this->enumName}
{
    {$constantDefinitions}

    /**
     * @return array of values for the type {$this->enumName}
     */
    public static function getAll()
    {
        return {$constantsArray};
    }

    /**
     * @param mixed \$key a candidate {$this->enumName} value
     *
     * @return true if \$key is in the list of known values for the type
     */
    public static function exists(\$key)
    {
        return in_array(\$key, self::getAll(), true);
    }
}

CODE;
    }
}
