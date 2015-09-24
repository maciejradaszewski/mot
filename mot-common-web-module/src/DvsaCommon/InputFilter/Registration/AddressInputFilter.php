<?php
/**
 * This file is part of the DVSA MOT Common Web project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommon\InputFilter\Registration;

use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

/**
 * (Account registration) Your address' step input filter.
 *
 * Class AddressInputFilter
 */
class AddressInputFilter extends InputFilter
{
    /** To be used by address line 1,2,3 and the "Town and city" field, since have identical criteria */
    const LIMIT_ADDRESS_MAX = 50;

    const LIMIT_POSTCODE_MAX = 10;

    /** Address line 1 */
    const FIELD_ADDRESS_1 = 'address1';
    const MSG_ADDRESS_EMPTY = 'you must enter address line 1';
    const MSG_ADDRESS_MAX = 'must be %d, or less, characters long';

    /** Address line 2, optional with the same maximum limit as line one */
    const FIELD_ADDRESS_2 = 'address2';

    /** Address line 3, optional with the same maximum limit as line one */
    const FIELD_ADDRESS_3 = 'address3';

    /** Town or city */
    const FIELD_TOWN_OR_CITY = 'townOrCity';
    const MSG_TOWN_OR_CITY_EMPTY = 'you must enter a town or city';

    /** Postcode */
    const FIELD_POSTCODE = 'postcode';
    const MSG_POSTCODE_EMPTY = 'you must enter a valid postcode';
    const MSG_POSTCODE_MAX = 'must be %d, or less, characters long';

    public function init()
    {
        $this->initValidatorsForAddress(AddressInputFilter::FIELD_ADDRESS_1, true);
        $this->initValidatorsForAddress(AddressInputFilter::FIELD_ADDRESS_2);
        $this->initValidatorsForAddress(AddressInputFilter::FIELD_ADDRESS_3);
        $this->initValidatorsForTownAndCity();
        $this->initValidatorsForPostcode();
    }

    /**
     * Adding validators for the address line 1,2 and 3 field/input.
     *
     * @param string     $fieldName  @see self::FIELD_ADDRESS_*
     * @param bool|false $isRequired (optional)
     */
    private function initValidatorsForAddress($fieldName, $isRequired = false)
    {
        $input = [

            'name'       => $fieldName,
            'required'   => $isRequired,
            'validators' => [
                [
                    'name'    => StringLength::class,
                    'options' => [
                        'max'     => self::LIMIT_ADDRESS_MAX,
                        'message' => sprintf(self::MSG_ADDRESS_MAX, self::LIMIT_ADDRESS_MAX),
                    ],
                ],
            ],
        ];

        if ($isRequired) {
            $input['validators'][] = [
                'name'    => NotEmpty::class,
                'options' => [
                    'message' => self::MSG_ADDRESS_EMPTY,
                ],
            ];
        }

        $this->add($input);
    }

    /**
     * Adding validators for the Town and city field/input.
     */
    private function initValidatorsForTownAndCity()
    {
        $this->add(
            [
                'name'       => self::FIELD_TOWN_OR_CITY,
                'required'   => true,
                'validators' => [
                    [
                        'name'    => NotEmpty::class,
                        'options' => [
                            'message' => self::MSG_TOWN_OR_CITY_EMPTY,
                        ],
                    ],
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'max'     => self::LIMIT_ADDRESS_MAX,
                            'message' => sprintf(self::MSG_ADDRESS_MAX, self::LIMIT_ADDRESS_MAX),
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Adding validators for the postcode field/input.
     */
    private function initValidatorsForPostcode()
    {
        $this->add(
            [
                'name'       => self::FIELD_POSTCODE,
                'required'   => true,
                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'max'     => self::LIMIT_POSTCODE_MAX,
                            'message' => sprintf(self::MSG_POSTCODE_MAX, self::LIMIT_POSTCODE_MAX),
                        ],
                    ],
                    [
                        'name'    => NotEmpty::class,
                        'options' => [
                            'message' => self::MSG_POSTCODE_EMPTY,
                        ],
                    ],
                ],
            ]
        );
    }
}
