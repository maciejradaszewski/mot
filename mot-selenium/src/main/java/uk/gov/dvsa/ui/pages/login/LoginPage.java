package uk.gov.dvsa.ui.pages.login;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.domain.navigation.MotPageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.userregistration.CreateAnAccountPage;

public class LoginPage extends Page {

    private static final String PAGE_TITLE = "MOT testing service";
    public static final String PATH = "/";

    @FindBy(id = "create-an-account")
    private WebElement createAnAccountLink;
    @FindBy(id = "forgotten-or-change-password")
    private WebElement forgottenOrChangePasswordLink;
    @FindBy(id = "do-not-have-account-section")
    private WebElement doNotHaveAccountSection;
    @FindBy(xpath = "//*[contains(@id,'_tid1')]")
    private WebElement userIdInput;
    @FindBy(xpath = "//*[contains(@id,'_tid2')]")
    private WebElement userPasswordInput;
    @FindBy(name = "Login.Submit")
    private WebElement submitButton;
    @FindBy(id = "validationErrors")
    private WebElement validationSummary;
    @FindBy(xpath = "(//script[contains(text(),'dataLayer')])[1]") private WebElement googleTagManagerDataLayer;

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

    public void expandDoNotHaveAccountSection() {
        doNotHaveAccountSection.click();
    }

    public <T extends Page> T login(String userName, String password, Class<T> clazz) {
        userIdInput.sendKeys(userName);
        userPasswordInput.sendKeys(password);
        submitButton.click();
        return MotPageFactory.newPage(driver, clazz);
    }

    public boolean isGoogleTagManagerDataLayerRendered() {
        return googleTagManagerDataLayer.isEnabled();
    }

    public boolean isSignInButtonPresent() {
        return submitButton.isDisplayed();
    }

    public boolean isValidationSummaryDisplayed() { return validationSummary.isDisplayed(); }
}
