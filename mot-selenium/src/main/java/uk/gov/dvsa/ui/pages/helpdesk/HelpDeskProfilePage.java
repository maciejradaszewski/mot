package uk.gov.dvsa.ui.pages.helpdesk;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public abstract class HelpDeskProfilePage extends Page {
    private String pageTitle;

    public HelpDeskProfilePage(MotAppDriver driver, String pageTitle) {
        super(driver);
        this.pageTitle = pageTitle;
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), pageTitle);
    }

    public HelpDeskProfilePage updateEmailSuccessfully(String email) {
        return null;
    }

    public boolean isEmailUpdateSuccessful(String email) {
        return false;
    }
}
