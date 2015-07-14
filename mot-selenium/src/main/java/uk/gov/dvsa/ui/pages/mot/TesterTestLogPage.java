package uk.gov.dvsa.ui.pages.mot;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

public class TesterTestLogPage extends TestLogPage {
    private static final String PAGE_TITLE = "Test logs of Tester";
    public static final String PATH = "/mot-test-log";

    public TesterTestLogPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
    }
}
