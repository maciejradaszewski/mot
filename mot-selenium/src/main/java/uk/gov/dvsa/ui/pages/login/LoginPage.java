package uk.gov.dvsa.ui.pages.login;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.userregistration.CreateAnAccountPage;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class LoginPage extends Page {

    private static final String PAGE_TITLE = "MOT testing service";

    @FindBy(partialLinkText = "create an account") private WebElement createAnAccountLink;

    public LoginPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE) && createAnAccountLink.isDisplayed();
    }

    public CreateAnAccountPage clickCreateAnAccountLink() {
        createAnAccountLink.click();
        return new CreateAnAccountPage(driver);
    }

}
