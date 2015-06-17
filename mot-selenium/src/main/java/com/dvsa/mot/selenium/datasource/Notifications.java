package com.dvsa.mot.selenium.datasource;

public class Notifications {

    public final String assertion;

    public static final Notifications ASSERTION_ACCEPT_NOMINATION_DECISION =
            new Notifications("You have been assigned the role of");
    public static final Notifications ASSERTION_REJECT_NOMINATION_DECISION =
            new Notifications("You have rejected the role of");

    public static final Notifications ASSERTION_ACCEPTED_NOMINATION_NOTIFICATION =
            new Notifications("Nomination accepted");
    public static final Notifications ASSERTION_REJECTED_NOMINATION_NOTIFICATION =
            new Notifications("Nomination rejected");

    public static final Notifications ASSERTION_AEDM_ROLE_NOTIFICATION = new Notifications(
            "You have been assigned a role of Authorised Examiner Designated Manager");

    public static final Notifications ASSERTION_AED_ROLE =
            new Notifications("Authorised Examiner Delegate");

    public static final Notifications ASSERTION_TESTER_ROLE = new Notifications("Tester");

    public static final Notifications ASSERTION_SITE_MANAGER_ROLE =
            new Notifications("Site manager");

    public static final Notifications ASSERTION_SITE_ADMIN_ROLE = new Notifications("Site admin");

    public static final Notifications ASSERTION_ROLE_REMOVAL_NOTIFICATION = new Notifications
            ("You have removed the role of Tester from Bob Thomas Arctor Tester1");

    public static final Notifications ASSERTION_AED_ROLE_REMOVAL = new Notifications("You have removed the role of AUTHORISED-EXAMINER-DELEGATE from Pam Poovey");

    public Notifications(String assertion) {
        super();
        this.assertion = assertion;
    }

    public static String getRoleRemovalMessage(String testerName){
        return "You have removed the role of Tester from " + testerName;
    }
}


