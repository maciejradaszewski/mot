package uk.gov.dvsa.ui.pages.mot;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

public class TestAbandonedPage extends TestCancelledPage{
    private static final String PAGE_TITLE = "MOT test abandoned";

    public TestAbandonedPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
    }
}
