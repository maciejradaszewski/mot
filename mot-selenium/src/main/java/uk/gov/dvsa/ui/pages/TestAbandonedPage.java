package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.support.PageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class TestAbandonedPage extends TestCancelledPage{
    private static final String PAGE_TITLE = "MOT test abandoned";

    public TestAbandonedPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
    }
}
