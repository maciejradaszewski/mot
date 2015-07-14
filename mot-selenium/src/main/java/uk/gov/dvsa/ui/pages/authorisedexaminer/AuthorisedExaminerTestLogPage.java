package uk.gov.dvsa.ui.pages.authorisedexaminer;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.ui.pages.mot.TestLogPage;

public class AuthorisedExaminerTestLogPage extends TestLogPage {
    private static final String PAGE_TITLE = "Test logs of Authorised Examiner";
    public static final String PATH = "/authorised-examiner/%s/mot-test-log";

    public AuthorisedExaminerTestLogPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
    }
}
