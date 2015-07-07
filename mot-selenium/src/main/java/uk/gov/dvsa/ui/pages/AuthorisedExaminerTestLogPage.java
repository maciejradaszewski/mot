package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

public class AuthorisedExaminerTestLogPage extends TestLogPage {
    private static final String PAGE_TITLE = "Test logs of Authorised Examiner";
    public static final String PATH = "/authorised-examiner/%s/mot-test-log";

    public AuthorisedExaminerTestLogPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
    }
}
