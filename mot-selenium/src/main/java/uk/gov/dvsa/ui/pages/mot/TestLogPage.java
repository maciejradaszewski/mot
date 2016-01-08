package uk.gov.dvsa.ui.pages.mot;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public abstract class TestLogPage extends Page {
    private String page_title = "";

    @FindBy(id = "todayCount") private WebElement todayCount;

    public TestLogPage(MotAppDriver driver, String title) {
        super(driver);
        page_title = title;
        selfVerify();
    }

    @Override protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), page_title);
    }

    public String getTodayCount() {
        return todayCount.getText();
    }
}
