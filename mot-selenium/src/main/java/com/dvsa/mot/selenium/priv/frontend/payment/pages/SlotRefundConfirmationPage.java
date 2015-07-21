package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SearchForAePage;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class SlotRefundConfirmationPage extends BasePage {

    private static final String PAGE_TITLE = "SLOT REFUND CONFIRMATION";

    @FindBy(id = "successMessage") private WebElement successMessage;

    public SlotRefundConfirmationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getRefundSuccessMessage() {
        return successMessage.getText();
    }
    
    public static SlotRefundConfirmationPage navigateHereFromLoginAndRefundSlotsSuccessfully(WebDriver driver, Login login, String aeRef, int slots) {
        return SearchForAePage.navigateHereFromLoginPage(driver, login).searchForAeAndSubmit(aeRef)
                .clickRefundsLink().enterSlotsToBeRefunded(slots)
                .clickContinueToStartRefund().clickRefundSlotsButton();
    }

}
