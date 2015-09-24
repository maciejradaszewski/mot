package com.dvsa.mot.selenium.datasource;

public class Login {

    /**
     * Logins test data
     */
    public static final Login LOGIN_TESTER1 = new Login("Tester1", "Password1");
    public static final Login LOGIN_TESTER2 = new Login("Tester2", "Password1");
    public static final Login LOGIN_TESTER4 = new Login("Tester4", "Password1");
    public static final Login LOGIN_TESTER5 = new Login("Tester5", "Password1");
    public static final Login LOGIN_TESTER8 = new Login("Tester8", "Password1");
    public static final Login LOGIN_CATATESTER = new Login("catATester", "Password1");
    public static final Login LOGIN_CATBTESTER = new Login("catBTester", "Password1", true);
    public static final Login LOGIN_ENFTESTER = new Login("ft-Enf-tester", "Password1");
    public static final Login LOGIN_VE3 = new Login("ft-enf-tester-3", "Password1");
    public static final Login LOGIN_ENFTESTER4 = new Login("ft-enf-tester-4", "Password1");
    public static final Login LOGIN_AREA_OFFICE1 = new Login("areaoffice1user", "Password1");
    public static final Login LOGIN_FINANCE_USER = new Login("financeuser", "Password1");
    public static final Login LOGIN_AREA_OFFICE2 = new Login("areaoffice2user", "Password1");
    public static final Login LOGIN_SCHEME_MANAGEMENT = new Login("schememgt", "Password1");
    public static final Login LOGIN_SCHEME_USER = new Login("schemeuser", "Password1");
    public static final Login LOGIN_MANYVTSTESTER = new Login("manyvtstester", "Password1");
    public static final Login LOGIN_RESETUSERPWD = new Login("testNameCertif4","Password1");
    public static final Login LOGIN_MANYVTSTESTER_NOVTSTESTER =
            new Login("novtstester", "Password1");
    public static final Login LOGIN_NOSLOTSTESTER = new Login("noslotstester", "Password1");
    public static final Login DM_USER = new Login("dmUser", "Password1");
    public static final Login LOGIN_INVALID_USERNAME = new Login("invalidUser", "Password1");
    public static final Login LOGIN_INVALID_USERNAME_AND_PASSWORD =
            new Login("notAValidUser", "notAValidPassword");
    public static final Login LOGIN_AEDM = new Login("aedm", "Password1");
    public static final Login LOGIN_AED1 = new Login("aed1", "Password1");
    public static final Login LOGIN_AED2 = new Login("aed2", "Password1");
    public static final Login LOGIN_DEMO_TEST_USER = new Login("demotestuser", "Password1");
    public static final Login LOGIN_SITE_MANAGER = new Login("site-manager", "Password1");
    public static final Login LOGIN_TESTER_AT_VTS1 = new Login("testerAtVts1", "Password1");
    public static final Login LOGIN_SITE_ADMIN_AT_VTS1 = new Login("siteAdminAtVts1", "Password1");
    public static final Login LOGIN_SITE_MANAGER_AT_VTS1 =
            new Login("siteManagerAtVts1", "Password1");
    public static final Login LOGIN_ANOTHER_TESTER_AT_VTS1 =
            new Login("anotherTesterAtVts1", "Password1");

    public static final Login LOGIN_VTS_TESTER_1 = new Login("vts-tester-1", "Password1");
    public static final Login LOGIN_AEDM_2 = new Login("aedm-2", "Password1");
    public static final Login LOGIN_AED_3 = new Login("aed-3", "Password1");

    public static final Login LOGIN_CUSTOMER_SERVICE = new Login("csco", "Password1");
    public static final Login LOGIN_DVLA_CENTRAL_OPERATIVE = new Login("do", "Password1");

    public static final Login LOGIN_NOROLES = new Login("rbac-no-roles-user", "Password1");
    public static final Login LOGIN_SITE_MANAGER_AT_V123539 =
            new Login("vts-11-site-manager", "Password1");

    public final String username;
    public final String password;
    public final boolean isManyVtsTester;

    public Login(String username, String password) {
        this(username, password, false);
    }

    public Login(String username, String password, boolean isManyVtsTester) {
        this.username = username;
        this.password = password;
        this.isManyVtsTester = isManyVtsTester;
    }

    @Override public String toString() {
        return username;
    }
}
