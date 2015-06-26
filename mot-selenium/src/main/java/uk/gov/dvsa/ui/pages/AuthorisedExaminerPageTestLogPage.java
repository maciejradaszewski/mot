package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class AuthorisedExaminerPageTestLogPage extends Page {
    private static final String PAGE_TITLE = "Test logs of Authorised Examiner";
    public static final String PATH = "/authorised-examiner/%s/mot-test-log";

    @FindBy (id = "todayCount") private WebElement todayCount;


    public AuthorisedExaminerPageTestLogPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public String getTodayCount() {
        return todayCount.getText();
    }
}
