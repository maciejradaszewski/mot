package uk.gov.dvsa.ui.pages;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.ui.pages.authorisedexaminer.AuthorisedExaminerViewPage;

public class AreaOfficerAuthorisedExaminerViewPage extends AuthorisedExaminerViewPage {

    private static final String PAGE_TITLE = "Full Details of Authorised Examiner";

    public AreaOfficerAuthorisedExaminerViewPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
    }

}
