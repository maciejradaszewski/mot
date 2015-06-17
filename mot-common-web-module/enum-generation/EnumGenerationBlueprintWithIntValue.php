<?php

namespace EnumGeneration;

/**
 * An extension of EnumGenerationBlueprint which handles int valued constants.
 */
class EnumGenerationBlueprintWithIntValue extends EnumGenerationBlueprint
{
    /**
     * @param array $row key/value pairs of column_name to row_value
     *
     * @return string Definition of constant in format "const CONSTANT_NAME = constant_value;"
     */
    protected function mapRowToConstantDefinition($row)
    {
        return sprintf(
            "const %s = %s;",
            $this->textToConstant($row[$this->columnNameForEnumKey]),
            $row[$this->columnNameForEnumValue]
        );
    }
}
