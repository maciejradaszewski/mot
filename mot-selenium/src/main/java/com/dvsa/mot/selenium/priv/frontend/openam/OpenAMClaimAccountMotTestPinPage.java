package com.dvsa.mot.selenium.priv.frontend.openam;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class OpenAMClaimAccountMotTestPinPage extends BasePage {

    @FindBy(id = "go-to-home") private WebElement continueToMotTestingServiceButton;

    @FindBy(id = "claim-account-pin") private WebElement claimAccountPin;

    @FindBy(className = "banner__heading") private WebElement pinHeading;

    @FindBy(className = "lead") private WebElement leadHeading;

    @FindBy(className = "text") private WebElement pageContentText;

    public OpenAMClaimAccountMotTestPinPage(WebDriver driver) {
        super(driver);
    }

    public UserDashboardPage clickContinueToTheMotTestingService() {
        continueToMotTestingServiceButton.click();
        return new UserDashboardPage(driver);
    }

    public String getPinHeadingText() {
        return pinHeading.getText();
    }

    public String getLeadHeadingText() {
        return leadHeading.getText();
    }

    public String getPageContentText() {
        return pageContentText.getText();
    }

    public boolean isPinNumberDisplayed() {
        return claimAccountPin.isDisplayed();
    }
}
