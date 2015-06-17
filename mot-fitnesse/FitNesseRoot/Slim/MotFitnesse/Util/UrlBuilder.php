<?php

namespace MotFitnesse\Util;

/**
 * Class UrlBuilder
 *
 * @package MotFitnesse\Util
 */
class UrlBuilder extends AbstractUrlBuilder
{
    const HOME = '/';
    const APPLICATION = '/application[/:id]';
    const AUTHORISED_EXAMINER_DESIGNATED_MANAGER = '/application/:uuid/designated-manager[/:aedmId]';
    const ORGANISATION = '/application/:uuid/organisation';
    const CONTACT_DETAILS = '/application/:uuid/contact-details';
    const STATUS = '/application/:uuid/status';
    const PRINCIPAL = '/application/:uuid/principal[/:id]';
    const CONVICTION = '/application/:uuid/conviction[/:id]';
    const TESTER_APPLICATION = '/tester-application[/:uuid]';
    const TESTER_APPLICATION_CONVICTION = '/conviction';
    const TESTER_APPLICANT = '/tester-applicant/:testerId';
    const VEHICLE_TEST_CLASS = '/vehicle-test-class[/:id]';
    const EXAMINING_BODY = '/examining-body';
    const TESTER_APPLICATION_STATUS = '/status';
    const TESTER_APPLICATION_EXPERIENCE = '/experience';
    const TESTER_APPLICATION_QUALIFICATION = '/qualification';
    const TESTER_ACCOUNT = '/tester-account';
    const REGISTRATION_COMPLETE = '/registration-complete/:id';
    const INDEX = '/';
    const SESSION = '/session[/:id]';
    const USER = '/user[/:id]';
    const PERSON_MOT_TEST_IN_PROGRESS = '/person/:id/current-mot-test';
    const PERSON_SITE_COUNT = '/person/:id/site-count';
    const PERSONAL_DETAILS = '/personal-details/:id';
    const SPECIAL_NOTICE = '/person/:id/special-notice[/:snId]';
    const SEARCH_PERSON = '/search-person';
    const SPECIAL_NOTICE_BROADCAST = '/special-notice-broadcast';
    const SPECIAL_NOTICE_CREATE = '/special-notice-content';
    const SPECIAL_NOTICE_CONTENT = '/special-notice-content[/:id]';
    const SPECIAL_NOTICE_PUBLISH = '/special-notice-content/:id/publish';
    const MOT_RETEST = '/mot-retest[/:motTestNumber]';
    const MOT_TEST = '/mot-test[/:motTestNumber]';
    const MOT_TEST_OPTIONS = '/options';
    const MOT_TEST_FIND_MOT_TEST_NUMBER = '/find-mot-test-number';
    const MOT_TEST_STATUS = '/status';
    const TEST_ITEM_SELECTOR = '/test-item-selector/:tisId';
    const REASON_FOR_REJECTION = '/reason-for-rejection';
    const REASONS_FOR_REJECTION = '/reasons-for-rejection[/:motTestRfrId]';
    const BRAKE_TEST_RESULT = '/brake-test-result';
    const ODOMETER = '/odometer-reading';
    const CERT_CHANGE_DIFF_TESTER_REASON = '/cert-change-diff-tester-reason';
    const MOT_TEST_RESULT = '/mot-test/:motTestNumber';
    const MOT_TEST_RESULT_COMPARE = '/mot-test/:motTestNumber/compare';
    const MOT_TEST_SEARCH = '/mot-test-search';
    const MOT_TEST_REFUSAL = '/mot-test-refusal[/:id]';
    const TESTER = '/tester[/:id]';
    const TESTER_EXPIRY = '/tester-expiry';
    const VEHICLE = '/vehicle[/:id]';
    const VEHICLE_LIST = '/vehicle/list';
    const TEST_ITEM_CATEGORY_NAME = '/test-item-category-name';
    const ASSESSMENT_APPLICATION_COMMENT = '/assessment/application/:uuid/comment';
    const VEHICLE_TESTING_STATION = '/vehicle-testing-station[/:id]';
    const VEHICLE_TESTING_STATION_BY_SITE = '/vehicle-testing-station/site/:site';
    const VEHICLE_TESTING_STATION_SEARCH = '/vehicle-testing-station-search/[:search]';
    const VEHICLE_SEARCH = '/vehicle-search';
    const DEFAULT_BRAKE_TESTS = "/default-brake-tests";
    const DATA_CATALOG = '/catalog';
    const ENFORCEMENT_MOT_TEST_RESULT = '/enforcement-mot-test-result[/:id]';
    const ENFORCEMENT_MOT_DEMO_TEST = '/enforcement/mot-demo-test[/:id]';
    const ENFORCEMENT_MOT_DEMO_TEST_SUBMIT = '/submit';

    const CERTIFICATE = '/certificate-print/:motTestId[/:variation][/dup]';
    const CONTINGENCY_CERTIFICATE = '/contingency-print/:name';

    const VTS_APPLICANT = '/vehicle-testing-station-application[/:uuid]';
    const VTS_APPLICANT_TESTING_FACILITIES = '/testing-facilities';
    const VTS_APPLICANT_EVIDENCE_OF_USE = '/evidence-of-use';
    const VTS_APPLICANT_PLANS_AND_DIMENSIONS = '/plans-and-dimensions';
    const VTS_APPLICANT_DOCUMENTS = '/documents';
    const VTS_APPLICANT_STATUS = '/status';

    const ORGANISATION_POSITION_NOMINATION = '/organisation/:id/position[/:positionId]';
    const ORGANISATION_ROLES = '/organisation/:id/person/:personId/role';

    const SITE = '/site';
    const SITE_ROLES = '/site/:siteId/person/:personId/role';
    const SITE_POSITION = '/site/:siteId/position[/:positionId]';

    const VISIT = '/visit';

    const MOT_TEST_COMPARE = '/mot-test/compare';

    const USER_ACCOUNT = '/user-account[/:id]';

    const NOTIFICATION = '/notification';
    const NOTIFICATION_PERSON_ID = '/person/:personId';

    const NOTIFICATION_BY_ID = '/notification/:id';
    const READ = '/read';
    const ACTION = '/action';

    const REPLACEMENT_CERTIFICATE_DRAFT = '/replacement-certificate-draft[/:id]';
    const REPLACEMENT_CERTIFICATE_DRAFT_APPLY = '/apply';

    const EQUIPMENT = '/equipment-model';

    const INSPECTION_LOCATION = '/inspection-location';

    const SLOTS = '/slots';

    const DD_SLOT_INCREMENT = '/slot-purchase/dd-slot-increment';

    const REINSPECTION_OUTCOME = '/reinspection-outcome';

    const SITE_OPENING_HOURS = "/opening-hours";
    const VTS_TEST_IN_PROGRESS = "/test-in-progress";

    const INTEGRATION_OPEN_INTERFACE = '/open-interface/mot-test-pass';
    const INTEGRATION_MOT_INFO = '/dvla-motinfo/mot-test-history';

    const ELASTIC_SEARCH_STATUS = '/es/status';
    const ELASTIC_SEARCH_STATUS_REBUILD = '/es/status/rebuild';
    const ELASTIC_SEARCH_REBUILD = '/es/rebuild/[:type]';
    const ELASTIC_SEARCH_RESET = '/es/reset';
    const ELASTIC_SEARCH_UNLOCK = '/es/unlock';

    const ORG_USAGE = '/organisation/:organisationId/slot-usage';
    const SITE_USAGE = '/site/:siteId/slot-usage';
    const USAGE_PERIOD_DATA = '/period-data';
    const SPECIAL_NOTICE_CONTENT_PUBLISH = '/special-notice-content/:id/publish';

    const AUTHORISED_EXAMINER = '/authorised-examiner[/:id]';
    const AUTHORISED_EXAMINER_BYAEREF = '/authorised-examiner/number[/:aenumber]';
    const MOT_TEST_LOG = '/mot-test-log[/:id]';
    const MOT_TEST_LOG_SUMMARY = '/summary';

    const MESSAGE = '/message';

    const EMERGENCY_LOG = '/emergency-log';

    const SECURITY_QUESTION = '/security-question';
    const SECURITY_QUESTION_ANSWER = '/security-question/check/:qid/:uid';

    const HELPDESK_MAILER = '/mailer/username-reminder';
    const RESET_PASSWORD  = '/reset-password';
    const CHANGE_PASSWORD = '/account/password-change';

    const ACCOUNT_CLAIM = '/account/claim[/:id]';

    const MOT_TEST_HISTORY = '/vehicle/:id/test-history';

    const VEHICLE_DICTIONARY = '/vehicle-dictionary';
    const MAKE = '/make[/:id]';
    const MODEL = '/model[/:id]';
    const MODEL_DETAILS = '/model-details';

    protected $routesStructure
        = [
            self::HOME                                   => '',
            self::APPLICATION                            => '',
            self::AUTHORISED_EXAMINER_DESIGNATED_MANAGER => '',
            self::ORGANISATION                           => '',
            self::CONTACT_DETAILS                        => '',
            self::STATUS                                 => '',
            self::PRINCIPAL                              => '',
            self::CONVICTION                             => '',
            self::TESTER_APPLICATION                     =>
                [
                    self::TESTER_APPLICANT                 => '',
                    self::VEHICLE_TEST_CLASS               => '',
                    self::TESTER_APPLICATION_EXPERIENCE    => '',
                    self::TESTER_APPLICATION_QUALIFICATION => '',
                    self::EXAMINING_BODY                   => '',
                    self::TESTER_APPLICATION_STATUS        => '',
                    self::TESTER_APPLICATION_CONVICTION    => '',
                ],
            self::TESTER_ACCOUNT                         =>
                [
                    self::REGISTRATION_COMPLETE => '',
                ],
            self::INDEX                                  => '',
            self::SESSION                                => '',
            self::USER                                   => '',
            self::PERSON_MOT_TEST_IN_PROGRESS            => '',
            self::PERSON_SITE_COUNT                      => '',
            self::PERSONAL_DETAILS                       => '',
            self::SEARCH_PERSON                          => '',
            self::SPECIAL_NOTICE                         => '',
            self::SPECIAL_NOTICE_BROADCAST               => '',
            self::SPECIAL_NOTICE_CREATE                  => '',
            self::SPECIAL_NOTICE_CONTENT                 => '',
            self::SPECIAL_NOTICE_CONTENT_PUBLISH         => '',
            self::SPECIAL_NOTICE_PUBLISH                 => '',
            self::MOT_RETEST                             => '',
            self::MOT_TEST                               =>
                [
                    self::MOT_TEST_FIND_MOT_TEST_NUMBER => '',
                    self::TEST_ITEM_SELECTOR      => '',
                    self::REASON_FOR_REJECTION  => '',
                    self::REASONS_FOR_REJECTION   => '',
                    self::BRAKE_TEST_RESULT       => '',
                    self::ODOMETER                => '',
                    self::MOT_TEST_STATUS         => '',
                    self::MOT_TEST_OPTIONS        => '',
                    self::TEST_ITEM_CATEGORY_NAME => '',
                ],
            self::MOT_TEST_SEARCH                        => '',
            self::MOT_TEST_REFUSAL                       => '',
            self::CERT_CHANGE_DIFF_TESTER_REASON         => '',
            self::MOT_TEST_RESULT                        => '',
            self::MOT_TEST_RESULT_COMPARE                => '',
            self::TESTER                                 => '',
            self::TESTER_EXPIRY                          => '',
            self::VEHICLE                                => '',
            self::VEHICLE_LIST                           => '',
            self::ASSESSMENT_APPLICATION_COMMENT         => '',
            self::VEHICLE_TESTING_STATION                => [
                self::SITE_OPENING_HOURS  => '',
                self::DEFAULT_BRAKE_TESTS => '',
                self::VTS_TEST_IN_PROGRESS => ''
            ],
            self::VEHICLE_TESTING_STATION_BY_SITE        => '',
            self::VEHICLE_TESTING_STATION_SEARCH         => '',
            self::DATA_CATALOG                           => '',
            self::ENFORCEMENT_MOT_TEST_RESULT            => '',
            self::ENFORCEMENT_MOT_DEMO_TEST              => [
                self::ENFORCEMENT_MOT_DEMO_TEST_SUBMIT => ''
            ],
            self::VTS_APPLICANT                          =>
                [
                    self::VTS_APPLICANT_TESTING_FACILITIES   => '',
                    self::VTS_APPLICANT_EVIDENCE_OF_USE      => '',
                    self::VTS_APPLICANT_PLANS_AND_DIMENSIONS => '',
                    self::VTS_APPLICANT_DOCUMENTS            => '',
                    self::VTS_APPLICANT_STATUS               => '',
                ],

            self::ORGANISATION_ROLES                     => '',
            self::ORGANISATION_POSITION_NOMINATION       => '',
            self::SITE                                   => '',
            self::SITE_ROLES                             => '',
            self::SITE_POSITION                          => '',
            self::VISIT                                  => '',
            self::MOT_TEST_COMPARE                       => '',
            self::USER_ACCOUNT                                => '',
            self::NOTIFICATION                           => [
                self::NOTIFICATION_PERSON_ID => ''
            ],
            self::NOTIFICATION_BY_ID                     => [
                self::READ   => '',
                self::ACTION => '',
            ],
            self::REPLACEMENT_CERTIFICATE_DRAFT          => [
                self::REPLACEMENT_CERTIFICATE_DRAFT_APPLY => '',
            ],
            self::EQUIPMENT => '',
            self::INSPECTION_LOCATION => '',
            self::DD_SLOT_INCREMENT => '',
            self::REINSPECTION_OUTCOME => '',
            self::INTEGRATION_OPEN_INTERFACE => '',
            self::INTEGRATION_MOT_INFO => '',

            self::ELASTIC_SEARCH_STATUS => '',
            self::ELASTIC_SEARCH_STATUS_REBUILD => '',
            self::ELASTIC_SEARCH_REBUILD => '',
            self::ELASTIC_SEARCH_RESET => '',
            self::ELASTIC_SEARCH_UNLOCK => '',
            self::CERTIFICATE => '',
            self::CONTINGENCY_CERTIFICATE  => '',
            self::VEHICLE_SEARCH => '',

            self::SITE_USAGE => [
                self::USAGE_PERIOD_DATA => '',
            ],
            self::AUTHORISED_EXAMINER => [
                self::MOT_TEST_LOG => [
                    self::MOT_TEST_LOG_SUMMARY => '',
                ],
            ],
            self::AUTHORISED_EXAMINER_BYAEREF => '',
            self::MESSAGE => '',
            self::EMERGENCY_LOG => '',
            self::SECURITY_QUESTION => '',
            self::SECURITY_QUESTION_ANSWER => '',
            self::ACCOUNT_CLAIM => '',
            self::MOT_TEST_HISTORY => '',
            self::HELPDESK_MAILER => '',
            self::RESET_PASSWORD => '',
            self::CHANGE_PASSWORD => '',
            self::MOT_TEST_HISTORY => '',
            self::VEHICLE_DICTIONARY                     => [
                self::MAKE => [
                    self::MODEL => [
                        self::MODEL_DETAILS => ''
                    ],
                ],
            ],
        ];

    protected function appendRoutesAndParams($element)
    {
        $this->routesAndParameters[]['route'] = $element;
        return $this;
    }

    public function home()
    {
        $this->routesAndParameters[]['route'] = self::HOME;

        return $this;
    }

    public static function vehicleDictionary()
    {
        return (new UrlBuilder())->appendRoutesAndParams(self::VEHICLE_DICTIONARY);
    }

    public function make($id = null)
    {
        if (null === $id) {
            return $this->appendRoutesAndParams(self::MAKE);
        }
        return $this->appendRoutesAndParams(self::MAKE)->routeParam('id', $id);
    }

    public function model($id = null)
    {
        if (null === $id) {
            return $this->appendRoutesAndParams(self::MODEL);
        }
        return $this->appendRoutesAndParams(self::MODEL)->routeParam('id', $id);
    }

    public function modelDetails()
    {
        return $this->appendRoutesAndParams(self::MODEL_DETAILS);
    }

    public function application()
    {
        $this->routesAndParameters[]['route'] = self::APPLICATION;

        return $this;
    }

    public function authorisedExaminerDesignatedManager()
    {
        $this->routesAndParameters[]['route'] = self::AUTHORISED_EXAMINER_DESIGNATED_MANAGER;

        return $this;
    }


    public function authorisedExaminerDesignatedManagerByAeRef()
    {
        $this->routesAndParameters[]['route'] = self::AUTHORISED_EXAMINER_BYAEREF;

        return $this;
    }
    public function organisation()
    {
        $this->routesAndParameters[]['route'] = self::ORGANISATION;

        return $this;
    }

    public function contactDetails()
    {
        $this->routesAndParameters[]['route'] = self::CONTACT_DETAILS;

        return $this;
    }

    public function status()
    {
        $this->routesAndParameters[]['route'] = self::STATUS;

        return $this;
    }

    public function principal()
    {
        $this->routesAndParameters[]['route'] = self::PRINCIPAL;

        return $this;
    }

    public function conviction()
    {
        $this->routesAndParameters[]['route'] = self::CONVICTION;

        return $this;
    }

    public function testerApplication()
    {
        $this->routesAndParameters[]['route'] = self::TESTER_APPLICATION;

        return $this;
    }

    public function testerApplicant()
    {
        $this->routesAndParameters[]['route'] = self::TESTER_APPLICANT;

        return $this;
    }

    public function vehicleTestClass()
    {
        $this->routesAndParameters[]['route'] = self::VEHICLE_TEST_CLASS;

        return $this;
    }

    public function examiningBody()
    {
        $this->routesAndParameters[]['route'] = self::EXAMINING_BODY;

        return $this;
    }

    public function testerApplicationStatus()
    {
        $this->routesAndParameters[]['route'] = self::TESTER_APPLICATION_STATUS;

        return $this;
    }

    public function testerApplicationExperience()
    {
        $this->routesAndParameters[]['route'] = self::TESTER_APPLICATION_EXPERIENCE;

        return $this;
    }

    public function testerApplicationQualification()
    {
        $this->routesAndParameters[]['route'] = self::TESTER_APPLICATION_QUALIFICATION;

        return $this;
    }

    public function testerApplicationConviction()
    {
        $this->routesAndParameters[]['route'] = self::TESTER_APPLICATION_CONVICTION;

        return $this;
    }

    public function testerAccount()
    {
        $this->routesAndParameters[]['route'] = self::TESTER_ACCOUNT;

        return $this;
    }

    public function registrationComplete()
    {
        $this->routesAndParameters[]['route'] = self::REGISTRATION_COMPLETE;

        return $this;
    }

    public function index()
    {
        $this->routesAndParameters[]['route'] = self::INDEX;

        return $this;
    }

    public function session()
    {
        $this->routesAndParameters[]['route'] = self::SESSION;

        return $this;
    }

    public function user()
    {
        $this->routesAndParameters[]['route'] = self::USER;

        return $this;
    }

    public function personMotTestInProgress()
    {
        $this->routesAndParameters[]['route'] = self::PERSON_MOT_TEST_IN_PROGRESS;

        return $this;
    }

    public function personGetSiteCount()
    {
        $this->routesAndParameters[]['route'] = self::PERSON_SITE_COUNT;

        return $this;
    }

    public function printCertificate()
    {
        $this->routesAndParameters[]['route'] = self::CERTIFICATE;
        return $this;
    }

    public function printContingencyCertificate()
    {
        $this->routesAndParameters[]['route'] = self::CONTINGENCY_CERTIFICATE;
        return $this;
    }

    public function personalDetails()
    {
        $this->routesAndParameters[]['route'] = self::PERSONAL_DETAILS;

        return $this;
    }

    public function searchPerson()
    {
        $this->appendRoutesAndParams(self::SEARCH_PERSON);

        return $this;
    }

    public function specialNotice()
    {
        $this->routesAndParameters[]['route'] = self::SPECIAL_NOTICE;

        return $this;
    }

    public function specialNoticeBroadcast()
    {
        $this->routesAndParameters[]['route'] = self::SPECIAL_NOTICE_BROADCAST;

        return $this;
    }

    public function specialNoticeCreate()
    {
        $this->routesAndParameters[]['route'] = self::SPECIAL_NOTICE_CREATE;

        return $this;
    }

    public function specialNoticeContent()
    {
        $this->routesAndParameters[]['route'] = self::SPECIAL_NOTICE_CONTENT;

        return $this;
    }

    public function specialNoticePublish()
    {
        $this->routesAndParameters[]['route'] = self::SPECIAL_NOTICE_PUBLISH;
        return $this;
    }

    public function motRetest()
    {
        $this->routesAndParameters[]['route'] = self::MOT_RETEST;

        return $this;
    }

    public function motTest()
    {
        $this->routesAndParameters[]['route'] = self::MOT_TEST;

        return $this;
    }

    public function motTestResultCompare()
    {
        $this->routesAndParameters[]['route'] = self::MOT_TEST_RESULT_COMPARE;

        return $this;
    }

    public function motTestResult()
    {
        $this->routesAndParameters[]['route'] = self::MOT_TEST_RESULT;

        return $this;
    }

    public function testItemSelector()
    {
        $this->routesAndParameters[]['route'] = self::TEST_ITEM_SELECTOR;

        return $this;
    }

    public function reasonForRejection()
    {
        $this->routesAndParameters[]['route'] = self::REASON_FOR_REJECTION;

        return $this;
    }

    public function reasonsForRejection()
    {
        $this->routesAndParameters[]['route'] = self::REASONS_FOR_REJECTION;

        return $this;
    }

    public function brakeTestResult()
    {
        $this->routesAndParameters[]['route'] = self::BRAKE_TEST_RESULT;

        return $this;
    }

    public function odometerReading()
    {
        $this->routesAndParameters[]['route'] = self::ODOMETER;

        return $this;
    }

    public function motTestStatus()
    {
        $this->routesAndParameters[]['route'] = self::MOT_TEST_STATUS;

        return $this;
    }

    public function certChangeDiffTesterReason()
    {
        $this->routesAndParameters[]['route'] = self::CERT_CHANGE_DIFF_TESTER_REASON;

        return $this;
    }

    public function motTestSearch()
    {
        $this->routesAndParameters[]['route'] = self::MOT_TEST_SEARCH;
        return $this;
    }

    public function motTestRefusal()
    {
        $this->routesAndParameters[]['route'] = self::MOT_TEST_REFUSAL;
        return $this;
    }

    public function tester()
    {
        $this->routesAndParameters[]['route'] = self::TESTER;
        return $this;
    }

    public function testerExpiry()
    {
        $this->routesAndParameters[]['route'] = self::TESTER_EXPIRY;
        return $this;
    }

    public function vehicle()
    {
        $this->routesAndParameters[]['route'] = self::VEHICLE;

        return $this;
    }

    public function vehicleList()
    {
        $this->routesAndParameters[]['route'] = self::VEHICLE_LIST;

        return $this;
    }

    public function assessmentApplicationComment()
    {
        $this->routesAndParameters[]['route'] = self::ASSESSMENT_APPLICATION_COMMENT;

        return $this;
    }

    public function vehicleTestingStation()
    {
        $this->routesAndParameters[]['route'] = self::VEHICLE_TESTING_STATION;

        return $this;
    }

    public function vehicleTestingStationBySiteNumber()
    {
        $this->routesAndParameters[]['route'] = self::VEHICLE_TESTING_STATION_BY_SITE;

        return $this;
    }

    public function vehicleTestingStationSearch()
    {
        $this->routesAndParameters[]['route'] = self::VEHICLE_TESTING_STATION_SEARCH;

        return $this;
    }


    public function vehicleSearch()
    {
        $this->routesAndParameters[]['route'] = self::VEHICLE_SEARCH;

        return $this;
    }

    public static function organisationRoles($organisationId, $personId)
    {
        $urlBuilder = new UrlBuilder();

        $urlBuilder->routesAndParameters[]['route'] = self::ORGANISATION_ROLES;
        $urlBuilder->routeParam('id', $organisationId);
        $urlBuilder->routeParam('personId', $personId);

        return $urlBuilder;
    }

    public static function organisationPositionNomination($organisationId, $positionId = null)
    {
        $urlBuilder = new UrlBuilder();

        $urlBuilder->routesAndParameters[]['route'] = self::ORGANISATION_POSITION_NOMINATION;
        $urlBuilder->routeParam('id', $organisationId);
        if ($positionId) {
            $urlBuilder->routeParam('positionId', $positionId);
        }

        return $urlBuilder;
    }

    public function site()
    {
        $this->routesAndParameters[]['route'] = self::SITE;

        return $this;
    }

    public static function siteRoles($siteId, $personId)
    {
        $urlBuilder = new UrlBuilder();

        $urlBuilder->routesAndParameters[]['route'] = self::SITE_ROLES;
        $urlBuilder->routeParam('siteId', $siteId);
        $urlBuilder->routeParam('personId', $personId);

        return $urlBuilder;
    }

    public static function sitePosition($siteId, $positionId = null)
    {
        $urlBuilder = new UrlBuilder();

        $urlBuilder->routesAndParameters[]['route'] = self::SITE_POSITION;
        $urlBuilder->routeParam('siteId', $siteId);
        if ($positionId) {
            $urlBuilder->routeParam('positionId', $positionId);
        }

        return $urlBuilder;
    }

    public function siteUsage($siteId)
    {
        return $this->appendRoutesAndParams(self::SITE_USAGE)->routeParam('siteId', $siteId);
    }

    public function periodData()
    {
        return $this->appendRoutesAndParams(self::USAGE_PERIOD_DATA);
    }

    public function visit()
    {
        $this->routesAndParameters[]['route'] = self::VISIT;

        return $this;
    }

    public function dataCatalog()
    {
        $this->routesAndParameters[]['route'] = self::DATA_CATALOG;

        return $this;
    }

    public function enforcementMotTestResult()
    {
        $this->routesAndParameters[]['route'] = self::ENFORCEMENT_MOT_TEST_RESULT;

        return $this;
    }

    public function vtsApplicant()
    {
        $this->routesAndParameters[]['route'] = self::VTS_APPLICANT;
        return $this;
    }

    public function vtsTestingFacilities()
    {
        $this->routesAndParameters[]['route'] = self::VTS_APPLICANT_TESTING_FACILITIES;
        return $this;
    }

    public function vtsEvidenceOfUse()
    {
        $this->routesAndParameters[]['route'] = self::VTS_APPLICANT_EVIDENCE_OF_USE;
        return $this;
    }

    public function vtsPlansAndDimensions()
    {
        $this->routesAndParameters[]['route'] = self::VTS_APPLICANT_PLANS_AND_DIMENSIONS;
        return $this;
    }

    public function vtsDocuments()
    {
        $this->routesAndParameters[]['route'] = self::VTS_APPLICANT_DOCUMENTS;
        return $this;
    }

    public function vtsStatus()
    {
        $this->routesAndParameters[]['route'] = self::VTS_APPLICANT_STATUS;
        return $this;
    }

    public static function userAccount()
    {
        $urlBuilder = new UrlBuilder();

        $urlBuilder->routesAndParameters[]['route'] = self::USER_ACCOUNT;

        return $urlBuilder;
    }

    //  @ARCHIVE VM-4532    function enforcementMotDemoTest()
    //  @ARCHIVE VM-4532    function enforcementMotDemoTestSubmit()

    public function compareMotTest()
    {
        return $this->appendRoutesAndParams(self::MOT_TEST_COMPARE);
    }

    public static function equipmentModel()
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->routesAndParameters[]['route'] = self::EQUIPMENT;

        return $urlBuilder;
    }

    public static function notificationForPerson($personId)
    {
        $urlBuilder = new UrlBuilder();

        $urlBuilder->routesAndParameters[]['route'] = self::NOTIFICATION;
        $urlBuilder->appendRoutesAndParams(self::NOTIFICATION_PERSON_ID);
        $urlBuilder->routeParam('personId', $personId);

        return $urlBuilder;
    }

    public static function notification($notificationId)
    {
        $urlBuilder = new UrlBuilder();

        $urlBuilder->routesAndParameters[]['route'] = self::NOTIFICATION_BY_ID;
        $urlBuilder->routeParam('id', $notificationId);

        return $urlBuilder;
    }

    public static function testItemCategoryName($motTestNumber)
    {
        $urlBuilder = new UrlBuilder();
        $urlBuilder->appendRoutesAndParams(self::MOT_TEST);
        $urlBuilder->routeParam('motTestNumber', $motTestNumber);
        $urlBuilder->appendRoutesAndParams(self::TEST_ITEM_CATEGORY_NAME);

        return $urlBuilder;
    }

    public function read()
    {
        return $this->appendRoutesAndParams(self::READ);
    }

    public function action()
    {
        return $this->appendRoutesAndParams(self::ACTION);
    }

    public function replacementCertificateDraft()
    {
        $this->routesAndParameters[]['route'] = self::REPLACEMENT_CERTIFICATE_DRAFT;
        return $this;
    }

    public function replacementCertificateDraftApply()
    {
        $this->routesAndParameters[]['route'] = self::REPLACEMENT_CERTIFICATE_DRAFT_APPLY;
        return $this;
    }

    public function inspectionLocation()
    {
        $this->routesAndParameters[]['route'] = self::INSPECTION_LOCATION;
        return $this;
    }

    public function directDebitSlotIncrement()
    {
        $this->routesAndParameters[]['route'] = self::DD_SLOT_INCREMENT;
        return $this;
    }

    public function reinspectionOutcome()
    {
        $this->routesAndParameters[]['route'] = self::REINSPECTION_OUTCOME;
        return $this;
    }

    public function siteOpeningHours()
    {
        return $this->appendRoutesAndParams(self::SITE_OPENING_HOURS);
    }

    public function vtsTestInProgress()
    {
        return $this->appendRoutesAndParams(self::VTS_TEST_IN_PROGRESS);
    }

    public function defaultBrakeTests()
    {
        return $this->appendRoutesAndParams(self::DEFAULT_BRAKE_TESTS);
    }

    public function integrationOpenInterface()
    {
        return $this->appendRoutesAndParams(self::INTEGRATION_OPEN_INTERFACE);
    }

    public function integrationMotInfo()
    {
        return $this->appendRoutesAndParams(self::INTEGRATION_MOT_INFO);
    }

    public function elasticSearchStatus()
    {
        return $this->appendRoutesAndParams(self::ELASTIC_SEARCH_STATUS);
    }

    public function elasticSearchStatusRebuild()
    {
        return $this->appendRoutesAndParams(self::ELASTIC_SEARCH_STATUS_REBUILD);
    }

    public function elasticSearchRebuild()
    {
        return $this->appendRoutesAndParams(self::ELASTIC_SEARCH_REBUILD);
    }

    public function elasticSearchReset()
    {
        return $this->appendRoutesAndParams(self::ELASTIC_SEARCH_RESET);
    }

    public function elasticSearchUnlock()
    {
        return $this->appendRoutesAndParams(self::ELASTIC_SEARCH_UNLOCK);
    }

    public static function motTestFindMotTestNumber()
    {
        return (new UrlBuilder())
            ->appendRoutesAndParams(self::MOT_TEST)
            ->appendRoutesAndParams(self::MOT_TEST_FIND_MOT_TEST_NUMBER);
    }

    public function authorisedExaminer($id = null)
    {
        $this->appendRoutesAndParams(self::AUTHORISED_EXAMINER);

        if ($id !== null) {
            $this->routeParam('id', $id);
        }
        return $this;
    }

    public static function motTestLog($ordId = null)
    {
        return self::of()->authorisedExaminer($ordId)->appendRoutesAndParams(self::MOT_TEST_LOG);
    }

    public static function motTestLogSummary($orgId)
    {
        return self::motTestLog($orgId)->appendRoutesAndParams(self::MOT_TEST_LOG_SUMMARY);
    }


    public function specialNoticeContentPublish()
    {
        return $this->appendRoutesAndParams(self::SPECIAL_NOTICE_CONTENT_PUBLISH);
    }

    public function message()
    {
        return $this->appendRoutesAndParams(self::MESSAGE);
    }

    public function emergencyLog()
    {
        return $this->appendRoutesAndParams(self::EMERGENCY_LOG);
    }

    public function securityQuestion()
    {
        return $this->appendRoutesAndParams(self::SECURITY_QUESTION);
    }

    public function securityQuestionAnswer()
    {
        return $this->appendRoutesAndParams(self::SECURITY_QUESTION_ANSWER);
    }

    public function accountClaim($userId)
    {
        return $this->appendRoutesAndParams(self::ACCOUNT_CLAIM)->routeParam('id', $userId);
    }

    public function genericMailer()
    {
        return $this->appendRoutesAndParams(self::HELPDESK_MAILER);
    }
    public static function motTestOptions($motTestNumber)
    {
        return (new UrlBuilder())
            ->appendRoutesAndParams(self::MOT_TEST)
            ->routeParam('motTestNumber', $motTestNumber)
            ->appendRoutesAndParams(self::MOT_TEST_OPTIONS);
    }

    public function testHistory($vehicleId)
    {
        return $this->appendRoutesAndParams(self::MOT_TEST_HISTORY)->routeParam('id', $vehicleId);
    }
    public function resetPassword()
    {
        return $this->appendRoutesAndParams(self::RESET_PASSWORD);
    }

    public function changePassword()
    {
        return $this->appendRoutesAndParams(self::CHANGE_PASSWORD);
    }
}
