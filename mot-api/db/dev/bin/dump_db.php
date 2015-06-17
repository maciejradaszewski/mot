<?php
/***/

include __DIR__ . '/lib/DbDumper.php';
include __DIR__ . '/lib/SqlScriptSaver.php';

/*
 * @file    dump_db.php => "Dumps database to sql scripts"
 * @author Patryk Jar <p.jar@kainos.com>
 *
 * Script to dump database according to $config array
 *
 * Steps:
 *  on local machine (for current develop branch):
 * ./dump_db.sh
 *
 * ---
 *  offline:
 *  run all tests on jenkins, if green - create a merge request
 *
 *  DONE
 */

$config = [
    /*
     * list of tables with static data that exist only in MOT2. These tables are releasable.
     */
    'static-data-mot2' => [
        'auth_for_testing_mot_role_map',
        'business_role_status',
        'configuration',
        'country_of_registration_lookup',
        'censor_blacklist',
        'direct_debit_history_status',
        'direct_debit_status',
        'empty_vrm_reason_lookup',
        'empty_vin_reason_lookup',
        'jasper_template',
        'jasper_template_type',
        'jasper_template_variation',
        'message_type',
        'non_working_day_country_lookup',
        'non_working_day_lookup',
        'notification_action_lookup',
        'notification_template',
        'notification_template_action',
        'organisation_business_role',
        'payment_status',
        'payment_type',
        'permission',
        'person_system_role',
        'role',
        'role_permission_map',
        'site_business_role',
        'special_notice_audience_type',
        'security_question',
        'test_slot_transaction_status',
    ],
    /*
    * list of tables with static data from legacy system.
    * They shouldn't be release. This data should be mapped from MOT1.
    */
    'static-data'      => [
        'auth_status',
        'auth_for_testing_mot_at_site_status',
        'auth_for_testing_mot_status',
        'auth_for_ae_status',
        'body_type',
        'brake_test_type',
        'certificate_change_different_tester_reason_lookup',
        'colour_lookup',
        'company_type',
        'country_lookup',
        'emergency_reason_lookup',
        'enforcement_condition_appointment_lookup',
        'enforcement_decision_category_lookup',
        'enforcement_decision_lookup',
        'enforcement_decision_outcome_lookup',
        'enforcement_decision_reinspection_outcome_lookup',
        'enforcement_decision_score_lookup',
        'enforcement_fuel_type_lookup',
        'enforcement_full_partial_retest_lookup',
        'enforcement_visit_outcome_lookup',
        'equipment_make',
        'equipment_model',
        'equipment_model_status',
        'equipment_model_vehicle_class_link',
        'equipment_status',
        'equipment_type',
        'facility_type',
        'fuel_type',
        'gender',
        'language_type',
        'licence_type',
        'make',
        'model',
        'model_detail',
        'mot_test_reason_for_cancel_lookup',
        'mot_test_status',
        'mot_test_type',
        'organisation_contact_type',
        'organisation_type',
        'organisation_site_status',
        'person_contact_type',
        'phone_contact_type',
        'qualification',
        'qualification_type',
        'mot_test_reason_for_refusal_lookup',
        'reason_for_rejection',
        'reason_for_rejection_type',
        'rfr_language_content_map',
        'rfr_vehicle_class_map',
        'site_contact_type',
        'site_type',
        'test_item_category',
        'test_item_category_vehicle_class_map',
        'ti_category_language_content_map',
        'title',
        'transmission_type',
        'vehicle_class_group',
        'vehicle_class',
        'visit_reason_lookup',
        'wheelplan_type',
        'event_type_lookup',
        'event_outcome_lookup',
        'event_category_lookup',
        'event_type_outcome_category_map',
        'dvla_model_model_detail_code_map',
        'transition_status',
        'weight_source_lookup',
    ],
    /*
     * List of tables that are used only for testing development and demo env. MUST not be released
     */
    'test-data'        => [
        'address',
        'auth_for_ae',
        'auth_for_testing_mot',
        'auth_for_testing_mot_at_site',
        'auth_for_ae_person_as_principal_map',
        'comment',
        'contact_detail',
        'direct_debit',
        'direct_debit_history',
        'dvla_vehicle',
        'email',
        'emergency_log',
        'enforcement_mot_test_result_witnesses',
        'enforcement_site_assessment',
        'equipment',
        'event',
        'event_person_map',
        'event_organisation_map',
        'event_site_map',
        'jasper_document',
        'jasper_document_variables',
        'licence',
        'organisation',
        'organisation_business_role_map',
        'organisation_contact_detail_map',
        'payment',
        'person',
        'person_contact_detail_map',
        'person_security_question_map',
        'person_system_role_map',
        'phone',
        'site',
        'site_business_role_map',
        'site_contact_detail_map',
        'site_facility',
        'site_testing_daily_schedule',
        'special_notice',
        'special_notice_audience',
        'special_notice_content',
        'test_slot_transaction',
        'vehicle',
        'vehicle_v5c',
    ],
];

$dbName = 'mot';
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = 'password';

$dbDumper = new DbDumper(new PDO("mysql:dbname={$dbName};host={$dbHost}", $dbUser, $dbPass));
$globalTableCount = 0;

echo date('H:i:s d-m-Y') . " Dumping db ({$dbName} on {$dbHost}) tables.\n";

foreach ($config as $folder => $listOfTables) {
    $scriptSaver = new SqlScriptSaver($dbDumper, __DIR__ . '/../populate/' . $folder);
    $tableCount = $scriptSaver->run($listOfTables);
    $globalTableCount += $tableCount;
    echo date('H:i:s d-m-Y') . ' Tables dumped (' . $folder . '): ' . $tableCount . "\n";
}

echo "---\n" . date('H:i:s d-m-Y') . ' Tables dumped (overall): ' . $globalTableCount . "\n";
