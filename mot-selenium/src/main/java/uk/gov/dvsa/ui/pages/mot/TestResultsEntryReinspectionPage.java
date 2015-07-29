package uk.gov.dvsa.ui.pages.mot;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class TestResultsEntryReinspectionPage extends TestResultsEntryPage {

    private static final String PAGE_TITLE = "MOT testing\n" +
            "MOT reinspection results entry";

        public TestResultsEntryReinspectionPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }
}
