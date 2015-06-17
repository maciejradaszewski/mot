package com.dvsa.mot.selenium.priv.frontend.openam;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class OpenAMClaimAccountMotTestPinPage extends BasePage {

    @FindBy(id = "btSubmitForm") private WebElement submitFormButton;

    @FindBy(id = "lead-paragraph") private WebElement leadParagraph;

    @FindBy(id = "tester-paragraph") private WebElement testerParagraph;

    @FindBy(id = "worn-paragraph") private WebElement wornParagraph;

    @FindBy(id = "claim-account-pin") private WebElement claimAccountPin;

    @FindBy(id = "go-to-previous-page") private WebElement goBack;

    public OpenAMClaimAccountMotTestPinPage(WebDriver driver) {
        super(driver);

    }

    public UserDashboardPage clickSaveAndContinue() {
        submitFormButton.click();
        return new UserDashboardPage(driver);
    }

    public String getTestersOnlyPinText() {
        return testerParagraph.getText();
    }

    public String getCommonPinLeadText() {
        return leadParagraph.getText();
    }

    public String getWornPinText() {
        return wornParagraph.getText();
    }

    public String getClaimAccountPinNumber() {
        return claimAccountPin.getText();
    }

    public boolean isTesterMessageDisplayed() {
        return isElementDisplayed(testerParagraph);
    }

    public OpenAMClaimAccountSecurityQuestionsPage goToSecurityQuestionsPage() {
        goBack.click();
        return new OpenAMClaimAccountSecurityQuestionsPage(driver);
    }

    public boolean isSamePinNumberDisplayed(String pin) {
        return getClaimAccountPinNumber().equalsIgnoreCase(pin);
    }

    public ChangePasswordPage clickSaveAndContinueExpectingChangePasswordPage() {
        submitFormButton.click();
        return new ChangePasswordPage(driver);
    }

}
