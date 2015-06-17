package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class EnforcementAeEventDetailsPage extends BasePage {

    private static String PAGE_TITLE = "FULL DETAILS OF AE EVENT SELECTED FOR";

    @FindBy(partialLinkText = "Go back")
    private WebElement goBackLink;

    public EnforcementAeEventDetailsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public EventHistoryPage clickGoBackLink() {
        goBackLink.click();
        return new EventHistoryPage(driver);
    }
}
