package uk.gov.dvsa.ui.pages.authorisedexaminer;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

public class AedmAuthorisedExaminerViewPage extends AuthorisedExaminerViewPage {
    private static final String PAGE_TITLE = "Authorised Examiner";

    public AedmAuthorisedExaminerViewPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
    }
}
