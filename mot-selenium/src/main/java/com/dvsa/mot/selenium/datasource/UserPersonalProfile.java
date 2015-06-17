package com.dvsa.mot.selenium.datasource;

public class UserPersonalProfile {

    public final String assertion;

    public static final UserPersonalProfile ASSERTION_SUCCESSFUL_TRAINING =
            new UserPersonalProfile("TESTER-APPLICANT-DEMO-TEST-REQUIRED");
    public static final UserPersonalProfile ASSERTION_UNSUCCESSFUL_TRAINING =
            new UserPersonalProfile("TESTER-APPLICANT-INITIAL-TRAINING-REQUIRED");
    public static final UserPersonalProfile ASSERTION_INITIAL_TRAINING =
            new UserPersonalProfile("TESTER-APPLICANT-INITIAL-TRAINING-REQUIRED");


    public UserPersonalProfile(String assertion) {
        super();
        this.assertion = assertion;
    }
}

