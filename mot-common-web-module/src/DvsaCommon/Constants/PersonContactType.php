<?php

namespace DvsaCommon\Constants;

use DvsaCommon\Utility\ArrayUtils;

/**
 * Class PersonContactType
 *
 * @package DvsaCommon\Constants
 */
class PersonContactType
{
    const PERSONAL = 'PERSONAL';
    const WORK = 'WORK';

    private $id;
    private $name;

    /**
     * @var PersonContactType
     */
    private static $personalContact;

    /**
     * @var PersonContactType
     */
    private static $workContact;

    private static $possibleValues;

    public static function personalContact()
    {
        return self::$personalContact;
    }

    public static function workContact()
    {
        return self::$workContact;
    }

    public static function getPossibleValues()
    {
        return self::$possibleValues;
    }

    /**
     * @param $id
     *
     * @return PersonContactType
     */
    public static function fromId($id)
    {
        $findByValuePredicate = function (PersonContactType $type) use ($id) {
            return $type->getId() === $id;
        };

        return ArrayUtils::firstOrNull(self::getPossibleValues(), $findByValuePredicate);
    }

    /**
     * @param $name
     *
     * @return PersonContactType
     */
    public static function fromName($name)
    {
        $findByNamePredicate = function (PersonContactType $type) use ($name) {
            return $type->getName() === $name;
        };

        return ArrayUtils::firstOrNull(self::getPossibleValues(), $findByNamePredicate);
    }

    public static function initialize()
    {
        self::$personalContact = new PersonContactType(1, self::PERSONAL);
        self::$workContact = new PersonContactType(2, self::WORK);

        self::$possibleValues = [
            self::personalContact(),
            self::workContact()
        ];
    }

    private function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }
}

PersonContactType::initialize();
