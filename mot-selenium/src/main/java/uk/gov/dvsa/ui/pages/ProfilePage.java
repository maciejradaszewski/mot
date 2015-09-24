package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class ProfilePage extends Page{

    public static final String path = "/profile";

    @FindBy (id = "full-address") private WebElement addressField;
    @FindBy (id = "email-address") private WebElement emailAddressField;
    @FindBy (id = "tester-qualification-status") private WebElement qualificationStatus;

    public ProfilePage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), driver.getCurrentUser().getFullName());
    }

    public boolean verifyPostCodeIsChanged(String postcode) {
        return addressField.getText().contains(postcode);
    }

    public boolean verifyEmailIsChanged(String email) {
        return emailAddressField.getText().equals(email);
    }

    public boolean isTesterQualificationStatusDisplayed() {
        return qualificationStatus.isDisplayed();
    }
}
