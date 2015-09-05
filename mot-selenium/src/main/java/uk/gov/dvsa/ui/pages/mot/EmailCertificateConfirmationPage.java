package uk.gov.dvsa.ui.pages.mot;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class EmailCertificateConfirmationPage extends Page {

    public static final String PATH = "/mot-test-certificate/1/email";
    private static final String PAGE_TITLE = "MOT testing\n" +
            "Certificate sent";

    public EmailCertificateConfirmationPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

}