package com.dvsa.mot.selenium.datasource;

/**
 * assertion (Validation, static application messages)
 */
public class Assertion {

    public static final Assertion ASSERTION_TEST_INCOMPLETE = new Assertion("Incomplete");


    public static final Assertion ASSERTION_PROFANITY_DETECTED =
            new Assertion("Profanity has been detected in the description of RFR");
    public static final Assertion ASSERTION_PURCHASE_SLOTS =
            new Assertion("No slots available, please purchase test slots");
    public static final Assertion ASSERTION_NO_VIN_NO_REG_MESSAGE =
            new Assertion("0 vehicles found without a registration and without a VIN.");
    public static final Assertion ASSERTION_ADDITIONAL_MESSAGE_FOR_NO_VIN_NO_REG =
            new Assertion("You must enter the registration mark and VIN to search for a vehicle.");
    public static final Assertion ASSERTION_ADDITIONAL_MESSAGE_FOR_LESS_OR_MORE_6CHAR_VIN =
            new Assertion(
                    "Only enter the last 6 digits of the VIN if you want to search for a partial match.");
    public static final Assertion ASSERTION_ADDITIONAL_MESSAGE_FOR_0_RESULTS_WITHOUT_VIN =
            new Assertion("You must enter the VIN if the vehicle has one.");
    public static final Assertion ASSERTION_ADDITIONAL_MESSAGE_FOR_0_RESULTS =
            new Assertion("Check the vehicle details are correct and try again.");
    public static final Assertion ASSERTION_VEHICLE_SEARCH_VIN_ENDING =
            new Assertion(" and a VIN ending in ");
    public static final Assertion ASSERTION_VEHICLE_SEARCH_VIN_MATCHING =
            new Assertion(" and a VIN matching ");
    public static final Assertion ASSERTION_VEHICLE_SEARCH_WITHOUT_VIN =
            new Assertion(" and without a VIN.");
    public static final Assertion ASSERTION_VEHICLE_SEARCH_MESSAGE_REG =
            new Assertion("0 vehicles found with registration ");
    public static final Assertion ASSERTION_VEHICLE_SEARCH_RESULTS_MESSAGE =
            new Assertion("1 vehicle found without a registration");
    public static final Assertion ASSERTION_VEHICLE_SEARCH_RESULTS_RETURNED =
            new Assertion("1 vehicle found with registration ");


    public static final Assertion ASSERTION_VEHICLE_SEARCH_WITHOUT_REG_NO_RESULTS =
            new Assertion("0 vehicles found without a registration");
    public static final Assertion ASSERTION_ADDITIONAL_MESSAGE_FOR_0_RESULTS_WITHOUT_REG_WRONG_VIN =
            new Assertion(
                    "You must enter the registration mark if the vehicle has one. Only enter the last 6 digits of the VIN if you want to search for a partial match.");
    public static final Assertion ASSERTION_ADDITIONAL_MESSAGE_FOR_0_RESULTS_WITHOUT_REG =
            new Assertion("You must enter the registration mark if the vehicle has one.");
    public static final Assertion ASSERTION_PRESERVE_MOT_EXPIRY_DATE_ADVICE = new Assertion(
            "Testing this vehicle today will not preserve the current expiry date. To preserve the date the earliest the vehicle can be tested is");

    //create new vehicle record
    public static final Assertion ASSERTION_SEARCH_CREATE_NEW_VEHICLE = new Assertion(
            "If you're unable to find a vehicle you can create a new vehicle record.");

    public static final Assertion ASSERTION_BRAKES_NOT_TESTED = new Assertion("Not tested");
    public static final Assertion ASSERTION_ODOMETER_UPDATED =
            new Assertion("Odometer reading updated");
    public static final Assertion ASSERTION_VEHICLE_CURRENTLY_UNDER_TEST =
            new Assertion("This vehicle is currently under test.");

    public static final Assertion ASSERTION_PIN_HEADING_MSG = new Assertion(
            "Your MOT testing service PIN is");
    public static final Assertion ASSERTION_MESSAGE_HEADING_WHAT_NEXT = new Assertion(
            "What happens next?");
    public static final Assertion ASSERTION_MESSAGE_PROVIDED_DETAILS= new Assertion(
            "When you sign in again you should use the details you have just provided.");
    public static final Assertion ASSERTION_MESSAGE_MEMORISE_YOUR_PIN = new Assertion(
            "Memorise your PIN, you will need it to perform key actions like performing an MOT test.");
    public static final Assertion ASSERTION_MESSAGE_RESET_PIN = new Assertion(
            "You can reset this at any time in the 'Your account' page.");
    public static final Assertion ASSERTION_CLAIM_CONFIRMATION_MSG =
            new Assertion("Your MOT testing service account has been claimed.");
    public static final Assertion ASSERTION_ACCOUNT_CLAIMED_BY =
            new Assertion("Account claimed by user ");
    public static final Assertion ASSERTION_USER_CLAIMS_ACCOUNT =
            new Assertion("User Claims Account");

    public static final Assertion ASSERTION_NOT_QUALIFIED_FOR_RETEST =
            new Assertion("Not qualified for a retest");
    public static final Assertion ASSERTION_ORIGINAL_TEST_PERFORMED_DIFFERENT_VTS =
            new Assertion("Original test was performed at a different VTS");
    public static final Assertion ASSERTION_ORIGINAL_TEST_CANCELLED =
            new Assertion("Original test was cancelled");
    public static final Assertion ASSERTION_ORIGINAL_TEST_PERFORMED_MORE_10_DAYS_AGO =
            new Assertion("Original test was performed more than 10 working days ago");
    public static final Assertion ASSERTION_ORIGINAL_TEST_NOT_PERFORMED =
            new Assertion("Original test never performed");
    public static final Assertion ASSERTION_ORIGINAL_TEST_NOT_FAILED =
            new Assertion("Original test was not failed");

    //Odometer Updates
    public static final Assertion ASSERTION_CURRENT_LOWER_THAN_PREVIOUS =
            new Assertion("This is lower than the last test");
    public static final Assertion ASSERTION_VALUE_SIGNIFICANTLY_HIGHER =
            new Assertion("This is significantly higher than the last test");
    public static final Assertion ASSERTION_CURRENT_EQUALS_PREVIOUS =
            new Assertion("This is the same as the last test");


    public final String assertion;

    public static final Assertion ASSERTION_PASS = new Assertion("Pass");
    public static final Assertion ASSERTION_FAIL = new Assertion("Fail");


    //Null vehicle search assertions
    public static final Assertion ASSERTION_VEHICLE_SEARCH_NULL =
            new Assertion("This field is required.");
    public static final Assertion ASSERTION_VRMS_FOUND_INFORMATION =
            new Assertion("Vehicle(s) found with registration mark ");
    public static final Assertion ASSERTION_VINS_FOUND_INFORMATION =
            new Assertion("Vehicle(s) found with VIN/chassis ");
    public static final Assertion ASSERTION_INVALID_VEHICLE_SEARCH =
            new Assertion("Search term(s) not found...");
    public static final Assertion ASSERTION_MULTIPLE_INVALID_MESSAGES =
            new Assertion("Search term(s) not found...\nThis field is required.");

    //Duplicate Replacement Certificates
    public static final Assertion ASSERTION_DUPLICATES_TEST_NOT_FOUND =
            new Assertion("Number does not match our records, check it and try again");
    public static final Assertion ASSERTION_DUPLICATES_MUST_ENTER_EITHER_V5C_OR_CERT_NUMBER =
            new Assertion("You must enter either the V5C number or the MOT certificate number");

    public static final Assertion ASSERTION_HELPDESK_ERROR_TOO_MANY_RESULTS =
            new Assertion("returned too many results. Add more details and try again.");
    public static final Assertion ASSERTION_HELPDESK_ERROR_NO_RESULTS = new Assertion(
            "returned no results. Check what you have entered or add more details and try again.");
    public static final Assertion ASSERTION_HELPDESK_ERROR_INPUT_REQUIRED = new Assertion(
            "You must enter information in at least one of the fields below to search for a user.");
    public static final Assertion ASSERTION_HELPDESK_ERROR_INCORRECT_DATE_FORMAT =
            new Assertion("The date of birth is not in the correct format.");
    public static final Assertion ASSERTION_HELPDESK_ERROR_INVALID_DATE =
            new Assertion("The date of birth is an invalid date.");

    public static final Assertion ASSERTION_NO_UNREAD_SPECIAL_NOTICE_MESSAGE =
            new Assertion("There are currently no notices in the back log.");
    public static final Assertion ASSERTION_SPECIAL_NOTICE_ACKNOWLEDGED =
            new Assertion("Special notice acknowledged");
    public static final Assertion ASSERTION_SPECIAL_NOTICE_REMOVED =
            new Assertion("Special notice removed");
    public static final Assertion ASSERTION_NOTICE_STATUS = new Assertion("Status: Draft");
    public static final Assertion ASSERTION_DATE_INPUT_NEEDED = new Assertion(
            "Please check the form\nThe input does not appear to be a valid date\nIncorrect date format, dd-mm-yyyy expected");

    //Security Question
    public static final Assertion ASSERTION_SECURITY_QUESTION_ONE =
            new Assertion("Security question one");
    public static final Assertion ASSERTION_SECURITY_QUESTION_1_FAIL =
            new Assertion("This is not the correct answer, you have 2 more tries");
    public static final Assertion ASSERTION_SECURITY_QUESTION_2_FAIL =
            new Assertion("This is not the correct answer, you have 1 more try");
    public static final Assertion ASSERTION_SECURITY_QUESTION_1_PASS =
            new Assertion("Question one correct");


    public static final Assertion ASSERTION_FORGOT_PASSWORD_USER_ACCOUNT_MSG = new Assertion(
            "First we need your user account so that we may retrieve your security questions...");
    public static final Assertion ASSERTION_FORGOT_PASSWORD_USER_ACCOUNT_REQUIRED =
            new Assertion("User account required");
    public static final Assertion ASSERTION_BEFORE_PASSWORD_CHANGE_MSG = new Assertion(
            "Before your password can be changed - you need to answer your two security questions correctly.");
    public static final Assertion ASSERTION_MSG_BEFORE_ANSWERING_SECOND_QUESTION = new Assertion(
            "Here is the second question to be answered correctly before you may change your password...");
    public static final Assertion ASSERTION_EMAIL_LINK_MSG = new Assertion(
            "We've sent you an email containing a link allowing you to change your password.");
    public static final Assertion ASSERTION_EMAIL_EXPIRATION_MSG = new Assertion(
            "If you haven't received the email within 15 minutes then please check your spam folder.");
    public static final Assertion ASSERTION_DVSA_HELPDESK_MSG = new Assertion(
            "If you are still having problems -\nPlease contact the DVSA Helpdesk on 0330 123 5654");
    public static final Assertion ASSERTION_DVSA_CONTACT_MSG = new Assertion(
            "Please contact the DVSA Helpdesk on 0330 123 5654\n"
                    + "Monday to Friday, 8:00am to 8:00pm\n" + "Saturday, 8:00am to 2:00pm\n"
                    + "Sunday, closed");
    public static final Assertion ASSERTION_EMAIL_VALIDITY_MSG = new Assertion(
            "You will need to use the link in the email provided within 1 hour 30 minutes, after which the link will expire.");
    public static final Assertion ASSERTION_BOTH_SECURITY_QUESTIONS_MSG = new Assertion(
            "Both security questions must be answered correctly before your password can be changed");
    public static final Assertion ASSERTION_INVALID_USER_ID =
            new Assertion("The user ID entered does not match our records");

    public static final Assertion ASSERTION_ASSESSMENT_CONFIRMATION_PAGE_TITLE =
            new Assertion("Assessment details saved");
    public static final Assertion ASSERTION_ASSESSMENT_CONFIRMATION_SUCCESS_MESSAGE = new Assertion(
            "The reinspection assessment outcome and details of the test differences have been saved");

    //Password reset
    public static final Assertion ASSERTION_RESET_PASSWORD_ARRIVAL_MESSAGE = new Assertion(
            "We've sent you an email containing a link allowing you to change your password.\n"
                    + "If you haven't received the email within 15 minutes then please check your spam folder.\n"
                    + "If you are still having problems -\n"
                    + "Please contact the DVSA Helpdesk on 0330 123 5654\n"
                    + "Monday to Friday, 8:00am to 8:00pm\n" + "Saturday, 8:00am to 2:00pm\n"
                    + "Sunday, closed\n"
                    + "You will need to use the link in the email provided within 1 hour 30 minutes, after which the link will expire.\n"
                    + "Sign in");
    public static final Assertion ASSERTION_RESET_PASSWORD_VALIDATION_MESSAGE = new Assertion(
            "You will need to use the link in the email provided within 1 hour 30 minutes, after which the link will expire.");


    public static final Assertion ASSERTION_PASSWORD_MISMATCH =
            new Assertion("The passwords you have entered do not match");
    public static final Assertion ASSERTION_PASSWORD_ILLEGAL =
            new Assertion("Password contains invalid characters, please try again");

    //CSCO Authentication
    public static final Assertion ASSERTION_DRIVER_LICENCE = new Assertion("GARDN605109C99LY60");
    public static final Assertion ASSERTION_TESTER_USERNAME = new Assertion("tester1");

    //Purchase Slots
    public static final Assertion ASSERTION_PURCHASE_SLOTS_BY_CARD_SUCCESS_MESSAGE = new Assertion(
            "Payment has been successful");
    public static final Assertion ASSERTION_FINANCE_USER_PURCHASE_SLOTS_BY_CHEQUE_SUCCESS_MESSAGE =
            new Assertion(
                    "Order is complete and the test slot balance has been successfully updated.");
    public static final Assertion ASSERTION_SEARCH_FOR_TRANSACTION_VALIDATION_ERROR_MESSAGE =
            new Assertion("Invalid receipt format");
    public static final Assertion ASSERTION_MANUAL_ADJUSTMENT_OF_SLOTS_SUCCESS_MESSAGE =
            new Assertion("An adjustment has been made");
    public static final Assertion ASSERTION_DIRECT_DEBIT_SETUP_SUCCESS_MESSAGE =
            new Assertion("Your direct debit has been successfully created");

    //DVSA User search
    public static final Assertion ASSERTION_TESTER_VTS = new Assertion("Popular Garages");
    public static final Assertion ASSERTION_USER_ADDRESS = new Assertion("Address");
    public static final Assertion ASSERTION_QUALIFICATION_STATUS_GROUP_QUALIFIED = new Assertion("Qualified");
    public static final Assertion ASSERTION_QUALIFICATION_STATUS_GROUP_DEMO_TEST_NEEDED = new Assertion("Demo Test Needed\n" +"Change qualification status");
    public static final Assertion ASSERTION_QUALIFICATION_STATUS_GROUP_DEMO_TEST_NEEDED_NO_PERMISSION = new Assertion("Demo Test Needed");

    public static final Assertion ASSERTION_EMAIL_WARNING = new Assertion(
            "The email address should be one you have easy and regular access to and will be used "
                    + "to send you important messages that will be required outside of the MOT "
                    + "Testing Service application - for example, username and password reminders.\n"
                    + "You could be without access for up to 5 working days "
                    + "if you don't supply an email address.");

    public static final Assertion ASSERTION_VALIDATION_MESSAGE =
            new Assertion("Both email addresses need to be the same");

    public static final Assertion ASSERTION_SITE_SEARCH =
            new Assertion("You need to enter some search criteria");

    public static final Assertion ASSERTION_SITE_INVALID_SEARCH =
            new Assertion("Unable to find any matches. Try expanding your search criteria");


    public static final Assertion ASSERTION_NO_USER_EXISTS = new Assertion("We could not find the user ");


    public Assertion(String assertion) {
        super();
        this.assertion = assertion;
    }
}
