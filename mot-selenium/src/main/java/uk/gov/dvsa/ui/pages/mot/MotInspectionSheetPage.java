package uk.gov.dvsa.ui.pages.mot;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.ui.pages.Page;

public class MotInspectionSheetPage extends Page {

    public MotInspectionSheetPage(MotAppDriver driver) {
        super(driver);
    }

    @Override
    protected boolean selfVerify() {
        return true;
    }
}
