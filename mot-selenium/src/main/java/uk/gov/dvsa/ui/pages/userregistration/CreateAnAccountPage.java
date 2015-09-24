package uk.gov.dvsa.ui.pages.userregistration;

import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class CreateAnAccountPage extends Page {

    private static final String PAGE_TITLE = "Create an account";

    public static final String PATH = "/account/register";

    @FindBy(id = "continue") private WebElement continueToNextPage;

    @FindBy(id = "signIn") private WebElement cancelAndReturn;

    public CreateAnAccountPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public DetailsPage details() {
        continueToNextPage.click();
        return new DetailsPage(driver);
    }
}
