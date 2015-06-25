<?php

use DvsaMotApi\Controller\CertificatePrintingController;
use DvsaMotApi\Controller\IdentityDataController;
use DvsaMotApi\Controller\MotTestController;
use DvsaMotApi\Controller\MotTestOptionsController;
use DvsaMotApi\Controller\MotTestSearchController;
use DvsaMotApi\Controller\MotTestShortSummaryController;
use DvsaMotApi\Controller\MotTestStatusController;
use DvsaMotApi\Controller\ReasonForRejectionController;
use DvsaMotApi\Controller\ReplacementCertificateDraftController;
use DvsaMotApi\Controller\RetestController;
use DvsaMotApi\Controller\TestItemCategoryNameController;
use DvsaMotApi\Controller\TesterMotTestLogController;
use DvsaMotApi\Factory\Controller\MotTestStatusControllerFactory;
use DvsaMotApi\Factory\Controller\TesterControllerFactory;
use DvsaMotApi\Factory\Controller\UserControllerFactory;
use DvsaMotApi\Factory\Controller\TesterMotTestLogControllerFactory;
use DvsaEntities\Entity\Person;
use DvsaCommon\Validator\UsernameValidator;
use DvsaMotApi\Factory\Controller\ReplacementCertificateDraftControllerFactory;

return [
    'controllers'     => [
        'invokables' => [
            'DvsaMotApi\Controller\Session' => \DvsaMotApi\Controller\SessionController::class,
            'DvsaMotApi\Controller\Index' => \DvsaMotApi\Controller\IndexController::class,
            RetestController::class => RetestController::class,
            MotTestController::class => MotTestController::class,
            'DvsaMotApi\Controller\MotTestRefusal' => \DvsaMotApi\Controller\MotTestRefusalController::class,
            'DvsaMotApi\Controller\DemoTest' => \DvsaMotApi\Controller\DemoTestController::class,
            'DvsaMotApi\Controller\TesterExpiry' => \DvsaMotApi\Controller\TesterExpiryController::class,
            'DvsaMotApi\Controller\Vehicle' => \DvsaMotApi\Controller\VehicleController::class,
            'DvsaMotApi\Controller\TesterSearch' => \DvsaMotApi\Controller\TesterSearchController::class,
            'DvsaMotApi\Controller\TestItemSelector' => \DvsaMotApi\Controller\TestItemSelectorController::class,
            TestItemCategoryNameController::class => TestItemCategoryNameController::class,
            ReasonForRejectionController::class => ReasonForRejectionController::class,
            'DvsaMotApi\Controller\MotTestOdometer' => \DvsaMotApi\Controller\MotTestOdometerController::class,
            'DvsaMotApi\Controller\MotTestCompare' => \DvsaMotApi\Controller\MotTestCompareController::class,
            MotTestSearchController::class => MotTestSearchController::class,
            'DvsaMotApi\Controller\EnforcementMotTestResult' =>
                \DvsaMotApi\Controller\EnforcementMotTestResultController::class,
            'DvsaMotApi\Controller\MotTestReasonForRejection' =>
                \DvsaMotApi\Controller\MotTestReasonForRejectionController::class,
            'DvsaMotApi\Controller\MotTestBrakeTestResult' =>
                \DvsaMotApi\Controller\MotTestBrakeTestResultController::class,
            'DvsaMotApi\Controller\MotTestBrakeTestConfigurationValidation' =>
                \DvsaMotApi\Controller\MotTestBrakeTestConfigurationValidationController::class,
            'DvsaMotApi\Controller\CertChangeDiffTesterReason' =>
                \DvsaMotApi\Controller\CertChangeDiffTesterReasonController::class,
            'DvsaMotApi\Controller\InspectionLocation' => \DvsaMotApi\Controller\InspectionLocationController::class,
            'DvsaMotApi\Controller\ReinspectionReport' => \DvsaMotApi\Controller\ReinspectionReportController::class,
            CertificatePrintingController::class => CertificatePrintingController::class,
            MotTestShortSummaryController::class => MotTestShortSummaryController::class,
            'DvsaMotApi\Controller\EmergencyLogController' => \DvsaMotApi\Controller\EmergencyLogController::class,
            MotTestOptionsController::class => MotTestOptionsController::class,
            IdentityDataController::class => IdentityDataController::class
        ],
        'factories' => [
            'DvsaMotApi\Controller\Tester' => TesterControllerFactory::class,
            'DvsaMotApi\Controller\User'   => UserControllerFactory::class,
            MotTestStatusController::class => MotTestStatusControllerFactory::class,
            TesterMotTestLogController::class => TesterMotTestLogControllerFactory::class,
            ReplacementCertificateDraftController::class => ReplacementCertificateDraftControllerFactory::class,
        ]
    ],

    UsernameValidator::class => [
        'options' => [
            'max' => Person::FIELD_USERNAME_LENGTH,
        ],
    ],
    'router'          => [
        'routes' => [
            'emergency-log' => [
                'type'    => 'segment',
                'options' => [
                    'route'       => '/emergency-log',
                    'defaults'    => [
                        'controller' => \DvsaMotApi\Controller\EmergencyLogController::class,
                    ],
                ],
            ],
            'certificate-print' => [
                'type'    => 'segment',
                'options' => [
                    'route'       => '/certificate-print/:id[/:dupmode]',
                    'constraints' => [
                        'dupmode' => 'dup'
                    ],
                    'defaults'    => [
                        'controller' => \DvsaMotApi\Controller\CertificatePrintingController::class,
                        'action'     => 'print'
                    ],
                ],
            ],
            'print-report'                   => [
                'type'    => 'segment',
                'options' => [
                    'route'       => '/print-report/:docId',
                    'constraints' => [
                        'docId' => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => \DvsaMotApi\Controller\CertificatePrintingController::class,
                        'action'     => 'printByDocId'
                    ],
                ],
            ],
            'contingency-print' => [
                'type'    => 'segment',
                'options' => [
                    'route'       => '/contingency-print/:name',
                    'constraints' => [
                        'name' => '(CT20|CT30|CT32)'
                    ],
                    'defaults'    => [
                        'controller' => \DvsaMotApi\Controller\CertificatePrintingController::class,
                        'action'     => 'printContingency'
                    ],
                ],
            ],
            'reinspection-outcome'            => [
                'type'    => 'literal',
                'options' => [
                    'route'    => '/reinspection-outcome',
                    'defaults' => [
                        'controller' => 'DvsaMotApi\Controller\ReinspectionReport',
                    ],
                ],
                'may_terminate' => true,
            ],
            'inspection-location'                          => [
                'type'    => 'literal',
                'options' => [
                    'route'    => '/inspection-location',
                    'defaults' => [
                        'controller' => 'DvsaMotApi\Controller\InspectionLocation',
                    ],
                ],
                'may_terminate' => true,
            ],
            'index'                          => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'DvsaMotApi\Controller\Index',
                    ],
                ],
            ],
            'session'                        => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/session[/:id]',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => 'DvsaMotApi\Controller\Session',
                    ],
                ],
            ],
            'identity-data'                        => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/identity-data',

                    'defaults'    => [
                        'controller' => IdentityDataController::class,
                    ],
                ],
            ],
            'user'                           => [
                'type'          => 'Segment',
                'options'       => [
                    'route'    => '/user[/:username]',
                    'defaults' => [
                        'controller' => 'DvsaMotApi\Controller\User',
                    ],
                ],
            ],
            'mot-retest'                     => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/mot-retest[/:id]',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => RetestController::class,
                    ],
                ],
            ],
            'mot-test-refusal'               => [
                'type'          => 'Segment',
                'options'       => [
                    'route'       => '/mot-test-refusal',
                    'defaults'    => [
                        'controller' => 'DvsaMotApi\Controller\MotTestRefusal'
                    ],
                ],
            ],
            'mot-demo-test' => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/mot-demo-test[/:id]',
                    'constraints' => [
                        'id' => '[0-9a-zA-Z]+',
                    ],
                    'defaults'    => [
                        'controller' => 'DvsaMotApi\Controller\DemoTest',
                    ],
                ],
            ],
            'mot-test'                       => [
                'type'          => 'Segment',
                'options'       => [
                    'route'       => '/mot-test[/:motTestNumber]',
                    'constraints' => [
                        'motTestNumber' => \DvsaCommon\Constants\MotTestNumberConstraint::FORMAT_REGEX,
                    ],
                    'defaults'    => [
                        'controller' => MotTestController::class,
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'minimal-mot' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/minimal',
                            'defaults' => [
                                'controller' => MotTestController::class,
                                'action'     => 'getMinimalMot'
                            ],
                        ],
                    ],
                    'find-mot-test-number' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/find-mot-test-number',
                            'defaults' => [
                                'controller' => MotTestController::class,
                                'action'     => 'findMotTestNumber'
                            ],
                        ],
                    ],
                    'test-item-selector'      => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/test-item-selector/:tisId',
                            'constraints' => [
                                'tisId' => '[0-9]+',
                            ],
                            'defaults'    => [
                                'controller' => 'DvsaMotApi\Controller\TestItemSelector',
                            ],
                        ],
                    ],
                    'test-item-category-name' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/test-item-category-name',
                            'defaults' => [
                                'controller' => TestItemCategoryNameController::class,
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'reason-for-rejection'  => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/reason-for-rejection',
                            'defaults' => [
                                'controller' => ReasonForRejectionController::class,
                            ],
                        ],
                    ],
                    'short-summary'      => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/short-summary',
                            'defaults'    => [
                                'controller' => MotTestShortSummaryController::class,
                            ],
                        ],
                    ],
                    'reasons-for-rejection'   => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/reasons-for-rejection[/:motTestRfrId]',
                            'constraints' => [
                                'motTestRfrId' => '[0-9]+',
                            ],
                            'defaults'    => [
                                'controller' => 'DvsaMotApi\Controller\MotTestReasonForRejection',
                            ],
                        ],
                    ],
                    'brake-test-result'       => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/brake-test-result',
                            'defaults' => [
                                'controller' => 'DvsaMotApi\Controller\MotTestBrakeTestResult',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'validate-configuration' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/validate-configuration',
                                    'defaults' => [
                                        'controller' => 'DvsaMotApi\Controller\MotTestBrakeTestConfigurationValidation'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'odometer'                => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/odometer-reading',
                            'defaults' => [
                                'controller' => 'DvsaMotApi\Controller\MotTestOdometer',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'reading-notices' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/notices',
                                    'defaults' => [
                                        'controller' => 'DvsaMotApi\Controller\MotTestOdometer',
                                        'action'     => 'getNotices'
                                    ],
                                ],
                            ],
                            'modify-check'    => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/modify-check',
                                    'defaults' => [
                                        'controller' => 'DvsaMotApi\Controller\MotTestOdometer',
                                        'action'     => 'canModifyOdometer'
                                    ],
                                ],
                            ],
                        ]
                    ],
                    'status'                  => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/status',
                            'defaults' => [
                                'controller' => MotTestStatusController::class,
                            ],
                        ],
                    ],
                    'compare'                 => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/compare',
                            'defaults' => [
                                'controller' => 'DvsaMotApi\Controller\MotTestCompare',
                            ],
                        ],
                    ],
                    'certificate-details'     => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/certificate-details[/:variation]',
                            'constraints' => [
                                'variation' => '[^/]+'
                            ],
                            'defaults' => [
                                'controller' => MotTestController::class,
                                'action'     => 'getCertificateDetails'
                            ],
                        ],
                    ],
                    'options'                 => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/options',
                            'defaults' => [
                                'controller' => MotTestOptionsController::class,
                            ],
                        ],
                    ],
                ],
            ],
            'mot-test-compare'           => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/mot-test/compare',
                    'defaults' => [
                        'controller' => 'DvsaMotApi\Controller\MotTestCompare',
                        'action'     => 'compareMotTest'
                    ],
                ],
            ],

            'mot-test-cert-number'           => [
                'type'    => 'Segment',
                'options'       => [
                    'route'       => '/mot-test-certificate',
                    'defaults'    => [
                        'controller' => MotTestController::class,
                        'action'     => 'getMotTestByNumber'
                    ],
                ],
            ],
            'replacement-certificate-draft'  => [
                'type'          => 'Segment',
                'options'       => [
                    'route'       => '/replacement-certificate-draft[/:id]',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => ReplacementCertificateDraftController::class,
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'replacement-certificate-draft-apply' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/apply',
                            'defaults' => [
                                'controller' => ReplacementCertificateDraftController::class,
                                'action'     => 'apply'
                            ],
                        ]
                    ],
                    'replacement-certificate-draft-diff'  => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/diff',
                            'defaults' => [
                                'controller' => ReplacementCertificateDraftController::class,
                                'action'     => 'diff'
                            ],
                        ]
                    ],
                ]

            ],
            'mot-test-search'                => [
                'type'          => 'Segment',
                'options'       => [
                    'route'    => '/mot-test-search',
                    'defaults' => [
                        'controller' => MotTestSearchController::class,
                        'action'     => 'getTests',
                    ],
                ],
            ],
            'cert-change-diff-tester-reason' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/cert-change-diff-tester-reason',
                    'defaults' => [
                        'controller' => 'DvsaMotApi\Controller\CertChangeDiffTesterReason',
                    ],
                ],
            ],
            'tester'                         => [
                'type'          => 'Segment',
                'options'       => [
                    'route'       => '/tester[/:id]',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => 'DvsaMotApi\Controller\Tester',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'full' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/full',
                            'defaults' => [
                                'controller' => 'DvsaMotApi\Controller\TesterSearch',
                            ],
                        ],
                    ],
                    'in-progress-test-id' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/in-progress-test-id',
                            'defaults' => [
                                'controller' => 'DvsaMotApi\Controller\Tester',
                                'action' => 'getInProgressTestId'
                            ]
                        ]
                    ],
                    'vehicle-testing-stations' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/vehicle-testing-stations',
                            'defaults' => [
                                'controller' => 'DvsaMotApi\Controller\Tester',
                                'action'     => 'getVehicleTestingStations'
                            ]
                        ]
                    ],
                    'vts-slot-balance' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/vts-slot-balance',
                            'defaults' => [
                                'controller' => 'DvsaMotApi\Controller\Tester',
                                'action'     => 'getVtsWithSlotBalance'
                            ]
                        ]
                    ],
                    'tester-mot-test-log' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/mot-test-log',
                            'defaults' => [
                                'controller' => TesterMotTestLogController::class,
                                'action'     => 'logData'
                            ]
                        ]
                    ],
                    'tester-mot-test-log-summary' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/mot-test-log/summary',
                            'defaults' => [
                                'controller' => TesterMotTestLogController::class,
                                'action'     => 'summary'
                            ]
                        ]
                    ],
                ],
            ],
            'vehicle'                        => [
                'type'          => 'Segment',
                'options'       => [
                    'route'       => '/vehicle',
                    'defaults'    => [
                        'controller' => 'DvsaMotApi\Controller\Vehicle',
                    ],
                ],
            ],
            'tester-expiry'       => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/tester-expiry',
                    'defaults' => [
                        'controller' => 'DvsaMotApi\Controller\TesterExpiry',
                    ],
                ],
            ],

            //  @ARCHIVE VM-4532    route to enforcement/mot-demo-test

            'enforcement-mot-test-result'    => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/enforcement-mot-test-result[/:id]',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => 'DvsaMotApi\Controller\EnforcementMotTestResult',
                    ],
                ],
            ],
        ],//end of 'routes =>'
    ], // end of 'router =>'
    'service_manager' => [
        'aliases' => [
            'translator' => 'MvcTranslator',
        ]
    ],
    'translator'      => [
        'locale'                    => 'en_US',
        'translation_file_patterns' => [
            [
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ],
        ],
    ],
];
