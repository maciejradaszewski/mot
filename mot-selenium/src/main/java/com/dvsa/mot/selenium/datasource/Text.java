package com.dvsa.mot.selenium.datasource;

/**
 * Text (Validation, static application messages)
 */
public class Text {

    public static final Text TEXT_ACCEPT = new Text("Accept");
    public static final Text TEXT_REJECT = new Text("Reject");
    public static final Text TEXT_PRINT = new Text("Print");
    public static final Text TEXT_RESULT_FAIL = new Text("FAIL");
    public static final Text TEXT_RESULT_PASS = new Text("PASS");
    public static final Text TEXT_RESULT_IN_PROGRESS = new Text("In progress");
    public static final Text TEXT_ENF_REINSPECTIONTESTCOMPLETE =
            new Text("Test results submitted successfully");

    public static final Text TEXT_ENF_SUMMARY = new Text("Summary");
    public static final Text TEXT_ENF_TARGETED_RE_INSPECTION = new Text("Targeted Reinspection");
    public static final Text TEXT_ENF_MOT_COMPLIANCE_SURVEY = new Text("MOT Compliance Survey");
    public static final Text TEXT_ENF_INVERTED_APPEAL = new Text("Inverted Appeal");
    public static final Text TEXT_ENF_STATUTORY_APPEAL = new Text("Statutory Appeal");
    public static final Text TEXT_ENF_COMPLAINT_REFERENCE_NUMBER = new Text("12345");
    public static final Text TEXT_DISREGARD = new Text("Disregard");
    public static final Text TEXT_MOT_TEST_EXPIRY_DATE = new Text("MOT Test Expiry Date");
    public static final Text TEXT_INCORRECT_DECISION = new Text("Incorrect decision");
    public static final Text TEXT_NOT_APPLICABLE = new Text("Not applicable");
    public static final Text TEXT_ENF_SCORE_ZERO =
            new Text("Judgement overruled but only marginally wrong");
    public static final Text TEXT_ENF_SCORE_FIVE = new Text("Judgement obviously wrong");
    public static final Text TEXT_ENF_SCORE_TEN =
            new Text("Judgement obviously significantly wrong");
    public static final Text TEXT_ENF_SCORE_TWENTY_ONE =
            new Text("Item failed has no defect (i.e. not an error of judgement)");
    public static final Text TEXT_ENF_SCORE_TWENTY_TWO = new Text("Item failed is not testable");
    public static final Text TEXT_ENF_SCORE_TWENTY_THREE =
            new Text("Other failable defect missed on a testable item");
    public static final Text TEXT_ENF_SCORE_THIRTY =
            new Text("Other excessive corrosion, wear or damage on a testable item missed");
    public static final Text TEXT_ENF_SCORE_FORTY = new Text(
            "Any defect missed that would, in VOSA DVSA's opinion, carry a risk of injury if driven further");
    public static final Text TEXT_ENF_CUTDOWN_SCORE_ZERO =
            new Text("0 - Overruled, marginally wrong");
    public static final Text TEXT_ENF_CUTDOWN_SCORE_FIVE = new Text("5 - Obviously wrong");
    public static final Text TEXT_ENF_CUTDOWN_SCORE_TEN = new Text("10 - Significantly wrong");
    public static final Text TEXT_ENF_CUTDOWN_SCORE_TWENTY_ONE =
            new Text("20 - Other defect missed");
    public static final Text TEXT_ENF_CUTDOWN_SCORE_TWENTY_TWO = new Text("20 - Not testable");
    public static final Text TEXT_ENF_CUT_DOWN_SCORE_TWENTY_NO_DEFECT = new Text("20 - No defect");
    public static final Text TEXT_ENF_CUT_DOWN_SCORE_THIRTY = new Text("30 - Exs. corr/wear/damage missed");
    public static final Text TEXT_ENF_CUTDOWN_SCORE_TWENTY_THREE =
            new Text("Other failable defect missed on a testable item");
    public static final Text TEXT_ENF_CUTDOWN_SCORE_THIRTY =
            new Text("30 - Exs. corr/wear/damage missed");
    public static final Text TEXT_ENF_CUTDOWN_SCORE_FORTY = new Text("40 - Risk of injury missed");
    public static final Text TEXT_ENF_NO_FURTHER_ACTION = new Text("No further action");
    public static final Text TEXT_ENF_ADVISORY_WARNING_LETTER = new Text("Advisory warning letter");
    public static final Text TEXT_ENF_COMPARISON_DAR = new Text("Disciplinary action report");
    public static final Text TEXT_NA = new Text("N/A");
    public static final Text TEXT_ENF_COMPARRISON_CATEGORY_IMMEDIATE = new Text("Immediate");
    public static final Text TEXT_ENF_AGREED_FULLY_WITH_TEST_RESULT =
            new Text("Agreed fully with test result");
    public static final Text TEXT_ENF_TEST = new Text("test");
    public static final Text TEXT_ONE = new Text("1");
    public static final Text TEXT_TWO = new Text("2");
    public static final Text TEXT_THREE = new Text("3");
    public static final Text TEXT_FOUR = new Text("4");
    public static final Text TEXT_FIVE = new Text("5");
    public static final Text TEXT_ENF_ROLE_1 = new Text("Tester");
    public static final Text TEXT_ENF_ROLE_2 = new Text("Boss");
    public static final Text TEXT_ENF_SUMMARY_COMMENT = new Text("Must do better");

    public static final Text TEXT_NOTIFICATION_REMOVED_ROLE = new Text("Removed Role");
    public static final Text TEXT_NOTIFICATION_NOMINATION_ACCEPTED =
            new Text("Nomination accepted");
    public static final Text TEXT_NOTIFICATION_NOMINATION_REJECTED =
            new Text("Nomination rejected");
    //Enforcement Advance Search
    public static final Text TEXT_ENF_ADV_SEARCH_NO_ITEMS_FOUND_RESULT =
            new Text("Search term(s) not found...");


    public static final Text TEXT_SPECIAL_NOTICE_REMOVED = new Text("Special notice removed");

    public static final String TEXT_ENF_ADV_SEARCH_VIN_NULL_RESULT = "Please enter a valid search";
    public static final String TEXT_ENF_ADV_SEARCH_VRM_NULL_RESULT = "Please enter a valid search";


    public static final String TEXT_ENF_ADV_SEARCH_INVALD_VIN_RESULT =
            "No results found for that vehicle";
    public static final String TEXT_ENF_ADV_SEARCH_INVALD_VRM_RESULT =
            "No results found for that registration";



    //Enforcement VM 1802 and 1803
    public static final String TEXT_ENF_MOT_SEARCH_INVALID_RECENT_TESTS =
            "No results found for that site";
    public static final String TEXT_ENF_MOT_SEARCH_INVALID_RECENT_TESTS_NULL =
            "Please enter a valid search";
    public static final String TEXT_ENF_MOT_SEARCH_INVALID_MONTH =
            "Please enter a value greater than or equal to 1.";
    public static final String TEXT_ENF_MOT_SEARCH_INVALID_ONE_MONTH_AND_A_YEAR =
            "Please enter a value greater than or equal to 1.\nPlease enter a valid year";
    public static final String TEXT_ENF_MOT_SEARCH_INVALID_FOR_TWO_MONTHS_AND_A_YEAR =
            "Please enter a value greater than or equal to 1.\nPlease enter a valid year\nPlease enter a value greater than or equal to 1.";
    public static final String TEXT_ENF_MOT_SEARCH_INVALID_FOR_TWO_MONTHS_AND_TWO_YEARS =
            "Please enter a value greater than or equal to 1.\nPlease enter a valid year\nPlease enter a value greater than or equal to 1.\nPlease enter a valid year";

    public static final String TEXT_JUSTIFICATION_MISSING = "Srinivas this needs to be updated.";

    // Enforcement MOT Test Numers

    public static final String TEXT_TESTER_2_STATUS = "Tester Active";
    public static final String TEXT_TESTER_2_GARAGE_ADDRESS = "67 Main Road";
    public static final String TEXT_TESTER_2_NAME = "Popular Garages";
    public static final String TEXT_VTS = "V1234";

    public static final String TEXT_VALID_ODOMETER_MILES = "12345";
    public static final String TEXT_VALID_ODOMETER_KM = "22440";
    public static final String TEXT_UPDATED_ODOMETER = "12345 miles";
    public static final String TEXT_UPDATED_VIN = "4S4BP67CX45487878";
    public static final String TEXT_UPDATED_REG = "H665R";
    public static final String TEXT_UPDATED_MAKE = "AUDI";
    public static final String TEXT_UPDATED_MODEL = "A3 1.6 AUTO";
    public static final String TEXT_STATUS_PASS = "Pass";
    public static final String TEXT_STATUS_FAIL = "Fail";
    public static final String TEXT_STATUS_ABANDONED = "Abandoned";

    //Vehicle Examiner
    public static final String TEXT_TEST_INCOMPLETE = "Incomplete";
    public static final String TEXT_ENTER_A_REASON_FOR_ABORTING_BY_VE = "VE became unwell";

    public static final String TEXT_NON_MOT_TITLE = "NON-MOT TEST RESULTS ENTRY";

    public static final String TEXT_PASSCODE = "123456";
    public static final String TEXT_PASSCODE_INVALID = "000000";
    public static final String TEXT_VEHICLE_WEIGHT_FIELD_VALIDATION_MESSAGE =
            "Please enter a valid vehicle weight";
    public static final String TEXT_RIDER_WEIGHT_FIELD_VALIDATION_MESSAGE =
            "Please enter a valid rider weight";
    public static final String TEXT_INVALID_SIDECAR_AND_VEHICLE_WEIGHTS_CLASSES_1_AND_2 =
            "Please enter a valid vehicle weight\nPlease enter a valid sidecar weight";
    public static final String TEXT_INVALID_WEIGHTS_CLASSES_1_AND_2 =
            "Please enter a valid vehicle weight\nPlease enter a valid rider weight\nPlease enter a valid sidecar weight";

    public static final String TEXT_ENF_REGISTRATION_SEARCH = "Registration (comparison available)";
    public static final String TEXT_ENF_VIN_SEARCH = "VIN/Chassis (comparison available)";
    public static final String TEXT_ENF_SITE_SEARCH = "Site (recent tests)";
    public static final String TEXT_ENF_SITE_DATE_RANGE_SEARCH = "Site (by date range)";
    public static final String TEXT_ENF_TESTER_DATE_RANGE_SEARCH = "Tester (by date range)";

    public final String text;

    //VM-1784
    public static final String TEXT_VRM_TYPE = "Registration (VRM)";
    public static final String TEXT_VIN_TYPE = "VIN/Chassis";

    public static final String TEXT_INVALID_OPENING_HOURS =
            "Invalid time format provided: minutes must be in increments of 30";
    public static final String TEXT_INVALID_PASSWORD_FOR_RESETTING_PIN =
            "The password you have entered does not match our records. Passwords are case sensitive, please check and try again.";

    // Contingency test
    public static final String TEXT_OTHER_REASON = "Some other reason not available in options";
    public static final String TEXT_CONTINGENCY_TEXT_CODE = "12345A";

    public static final String TEXT_SOMETHING_WENT_WRONG = "SOMETHING WENT WRONG!";

    public static final String TEXT_MOT_TEST_NUMBER = "MOT Test Number";
    public static final String TEXT_VEHICLE_REGISTRATION_MARK = "Vehicle Registration Mark";
    public static final String TEXT_VT20 = "VT20/1.0";
    public static final String TEXT_MAKE = "Make";
    public static final String TEXT_MODEL = "Model";
    public static final String TEXT_VEHICLE_IDENTIFICATION_NUMBER = "Vehicle Identification Number";
    public static final String TEXT_COLOUR = "Colour";
    public static final String TEXT_ODOMETER_READING = "Odometer Reading";
    public static final String TEXT_SIGNATURE_OF_ISSUER = "Signature of Issuer";
    public static final String TEXT_NOT_VALID = "NOT VALID";
    public static final String TEXT_ISSUERS_NAME = "Issuer's Name";
    public static final String TEXT_VT20_TITLE = "MOT Test Certificate";
    public static final String TEXT_VT30_TITLE = "Refusal of an MOT Test Certificate";
    public static final String TEXT_VT32_TITLE = "Advisory Notice";
    public static final String TEXT_VT20_WELSH_TITLE = "MOT Test CertificateTystysgrif Brawf MOT";
    public static final String TEXT_VT32_WELSH_TITLE = "Advisory NoticeHysbysiad Ymgynghorol";
    public static final String TEXT_VT30_WELSH_TITLE = "Refusal of an MOT Test CertificateGwrthodiad Tystysgrif Brawf MOT";
    public static final String TEXT_TESTER_JUSTIFICATION = "Tester Justification";
    public static final String TEXT_VE_JUSTIFICATION = "VE Justification";
    public static final String TEXT_COUNTRY_OF_REGISTRATION = "Country of Registration";
    public static final String TEXT_VT20_TYPE = "VT20";
    public static final String TEXT_VT30_TYPE = "VT30";
    // DVSA user search
    public static final String TEXT_ENTER_TOWN = "Bristol";

    public static final String TEXT_RESET_PASSWORD = "Reset123";
    public static final String TEXT_RESET_INVALID_PASSWORD = "InvalidPassword123";
    public static final String TEXT_DEFAULT_PASSWORD ="Password1";

    public static final String TEXT_SECURITY_ANSWER_1 = "Paris";
    public static final String TEXT_SECURITY_ANSWER_2 = "Engineer";
    public static final String TEXT_PASSWORD_2 = "Password2";
    public static final String ILLEGAL_PASSWORD="P£ssword123$";

    public Text(String text) {
        super();
        this.text = text;
    }
}
