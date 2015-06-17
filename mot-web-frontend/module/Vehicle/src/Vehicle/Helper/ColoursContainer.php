<?php

namespace Vehicle\Helper;

use DvsaCommon\Enum\ColourCode;
use DvsaCommon\Utility\ArrayUtils;

class ColoursContainer
{
    const NOT_STATED_TEXT = 'Not stated';
    const NO_OTHER_COLOUR_TEXT = 'No other colour';
    /** @var array */
    private $primaryColours;

    /** @var array */
    private $secondaryColours;

    /**
     * Sorts colours alphabetically and moves 'Not stated/No other colour' choice to the top.
     *
     * @param array $colours
     * @param bool  $prepareForZendForm creates list in an array format desirable by Zend_Form
     */
    public function __construct(array $colours, $prepareForZendForm = false)
    {
        asort($colours);
        $colours = ArrayUtils::moveElementToTop($colours, ColourCode::NOT_STATED);

        $this->primaryColours = $this->secondaryColours = $colours;

        $this->primaryColours[ColourCode::NOT_STATED] = self::NOT_STATED_TEXT;
        $this->secondaryColours[ColourCode::NOT_STATED] = self::NO_OTHER_COLOUR_TEXT;

        if ($prepareForZendForm) {
            $this->primaryColours = $this->toZendFormFormat($this->primaryColours);
            $this->secondaryColours = $this->toZendFormFormat($this->secondaryColours);
        }
    }

    private function toZendFormFormat(array $colours)
    {
        $coloursInZendFormat = [];

        foreach ($colours as $key => $value) {
            $coloursInZendFormat[] = [
                'id' => $key,
                'name' => $value,
            ];
        }

        return $coloursInZendFormat;
    }

    public function getPrimaryColours()
    {
        return $this->primaryColours;
    }

    public function getSecondaryColours()
    {
        return $this->secondaryColours;
    }
}
