package uk.gov.dvsa.ui.pages.specialnotices;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

import java.io.IOException;

public class SpecialNoticeAdminPage extends NoticePage {

    public static final String PATH = "/special-notices/all";

    public SpecialNoticeAdminPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    public boolean broadcastNotice(String username, String specialNoticeTitle) throws IOException {
        return broadcastSpecialNotice(username, specialNoticeTitle);
    }
}
