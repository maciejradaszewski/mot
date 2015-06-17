<?php

namespace DvsaCommonTest\Model;

use DvsaCommon\Model\PersonAuthorization;

/**
 * Unit tests for PersonAuthorization.
 */
class PersonAuthorizationTest extends \PHPUnit_Framework_TestCase
{

    private $originalArray
        = [
            "normal"              =>
                [
                    "roles"       => [
                        "NORMAL-ROLE-1",
                        "NORMAL-ROLE-2"
                    ],
                    "permissions" => [
                        "NORMAL-ROLE-1-PERMISSION-1",
                        "NORMAL-ROLE-1-PERMISSION-2",
                        "NORMAL-ROLE-2-PERMISSION-1"
                    ]
                ],
            "sites"               => [
                "10" => [
                    "roles"       => ["SITE-ROLE-A"],
                    "permissions" => ["SITE-ROLE-A-PERMISSION-1"]
                ],
                "20" => [
                    "roles"       => ["SITE-ROLE-A", "SITE-ROLE-B"],
                    "permissions" => ["SITE-ROLE-A-PERMISSION-1", "SITE-ROLE-B-PERMISSION-1"]
                ],
                "30" => [
                    "roles"       => ["SITE-ROLE-B"],
                    "permissions" => ["SITE-ROLE-B-PERMISSION-1"]
                ],
            ],
            "organisations"       => [
                "3" => [
                    "roles"       => ["ORGANISATION-ROLE-1"],
                    "permissions" => ["ORGANISATION-ROLE-1-PERMISSION-1"]
                ]
            ],
            "siteOrganisationMap" => [
                "10" => "1",
                "20" => "3",
                "30" => "2"
            ]
        ];

    public function testRoundtrip()
    {
        /** @var PersonAuthorization $personFromArray */
        $personFromArray = PersonAuthorization::fromArray($this->originalArray);
        $roundTripped = $personFromArray->asArray();

        $this->assertEquals($this->originalArray, $roundTripped);
    }

} 