package uk.gov.dvsa.ui.pages.vts.ChangeDetails;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;

public class ChangeDetailsEmailPage extends ChangeDetailsPage {
    public static final String PAGE_TITLE = "Change email address";
    public static final String PATH = "/vehicle-testing-station/%s/email/change";

    @FindBy (id = "email") private WebElement emailField;

    public ChangeDetailsEmailPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
    }

    public ChangeDetailsEmailPage inputContactDetailsEmail(String newEmail) {
        FormCompletionHelper.enterText(emailField, newEmail);
        return this;
    }
}

