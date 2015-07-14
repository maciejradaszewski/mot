package uk.gov.dvsa.ui.pages.mot;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

public class TestAbortedPage extends TestCancelledPage{
    private static final String PAGE_TITLE = "MOT test aborted";

    public TestAbortedPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
    }
}
