package com.dvsa.mot.selenium.priv.frontend.user;

import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class ResetSecurityPinConfirmationPage extends BasePage{

    @FindBy(id = "reset-pin") private WebElement resetPin;

    @FindBy(partialLinkText = "Cancel and return to your profile") private WebElement cancelLink;

    public ResetSecurityPinConfirmationPage(WebDriver driver) {
        super(driver);
        checkTitle(PageTitles.RESET_PIN.getPageTitle());
    }

    public ResetSecurityPinConfirmationPage(WebDriver driver, String title) {
        super(driver);
        checkTitle(title);
    }

    public SecurityPinReissuedPage clickResetPin(){
        resetPin.click();
        return new SecurityPinReissuedPage(driver);
    }
}
