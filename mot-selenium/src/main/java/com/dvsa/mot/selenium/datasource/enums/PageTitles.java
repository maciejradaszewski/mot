package com.dvsa.mot.selenium.datasource.enums;

public enum PageTitles {

    DVSA_MODERNISATION_PAGE("DVSA MODERNISATION"),
    NEW_VEHICLE_RECORD_COMPLETE_PAGE("NEW VEHICLE RECORD CREATED"),
    LOGIN_PAGE("Sign in to OpenAM"),
    VEHICLE_SEARCH_PAGE("FIND A VEHICLE"),
    BRAKE_TEST_RESULTS_PAGE("BRAKE TEST RESULTS"),
    VEHICLE_CONFIRMATION_PAGE("VEHICLE CONFIRMATION"),
    BRAKE_TEST_CONFIGURATION_PAGE("BRAKE TEST CONFIGURATION"),
    MOT_REINSPECTION_PAGE("MOT REINSPECTION RESULTS ENTRY"),
    MOT_REINSPECTION_TEST_ENTRY_PAGE("MOT TESTING\n" + "MOT REINSPECTION RESULTS ENTRY"),
    DUPLICATE_OR_REPLACEMENT_CERTIFICATE_PAGE("DUPLICATE OR REPLACEMENT CERTIFICATE"),
    ENFORCEMENT_HOME_PAGE("MOT"),
    ENFORCEMENT_USER_SEARCH_PAGE("User search"),
    CHANGE_SECURITY_SETTINGS_TITLE("CHANGE SECURITY SETTINGS"),
    MOT_RETEST_COMPLETE("MOT RE-TEST COMPLETE"),
    MOT_RETEST_RESULT_ENTRY_PAGE("MOT TESTING\n" + "MOT RE-TEST RESULTS ENTRY"),
    MOT_START_RETEST_CONFIRMATION_PAGE("MOT TESTING\n" + "START RETEST CONFIRMATION"),
    MOT_TEST_SUMMARY_PAGE("MOT TEST SUMMARY"),
    MOT_TEST_STARTED("MOT TESTING\n" + "MOT TEST STARTED"),
    MOT_VEHICLE_DETAILS_PAGE("Vehicle Details"),
    MOT_REINSPECTION_TEST_COMPLETE_PAGE("MOT reinspection complete"),
    LIST_OF_EVENTS_HISTORY_PAGE("EVENTS HISTORY\n"
            + "LIST OF AE EVENTS FOUND FOR ORGANISATION \"AE1438 - CITY FIXES LTD"),
    EVENT_FULL_DETAILS_PAGE("Full Details of AE Event selected for"),
    ORGANISATION_DETAILS_PAGE("AE-1438 - City and Guilds"),
    EVENT_FULL_DETAILS_OF_AE("Full Details of Authorised Examiner"),
    EVENT_SEARCH_AE("Search for AE"),
    AUTHORISED_EXAMINER_FULL_DETAILS("Full Details of Authorised Examiner\n"
            + " City Fixes Ltd"),
    UPDATE_PROFILE_DETAILS_TITLE("CHANGE DETAILS"),
    PERSONAL_PROFILE_TITLE("YOUR PROFILE"),
    RESET_PIN("MOT Testing Service\n" + "Reset PIN"),
    SITE_INFORMATION("Site search\n" + "Search for site information by..."),
    SITE_SEARCH_RESULTS("Site search\n" + "Results with ");

    private final String pageTitle;

    private PageTitles(String pageTitle) {
        this.pageTitle = pageTitle;
    }

    public String getPageTitle() {
        return pageTitle.toUpperCase();
    }
}
