package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Payments;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SearchForAePage;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class AdjustmentConfirmationPage extends BasePage {

    private static final String PAGE_TITLE = "MANUAL ADJUSTMENT OF SLOTS";

    @FindBy(id = "statusMessage") private WebElement statusMessage;

    @FindBy(id = "successMessage") private WebElement balanceMessage;

    public AdjustmentConfirmationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getManualAdjustmentStatusMessage() {
        return statusMessage.getText();
    }

    public String getAdjustedBalanceMessage() {
        return balanceMessage.getText();
    }
    
    public static AdjustmentConfirmationPage loginAndCompleteManualPositiveAdjustmentOfSlotBalance(WebDriver driver, Login login, String aeRef) {
        return SearchForAePage.navigateHereFromLoginPage(driver, login)
                .searchForAeAndSubmit(aeRef).clickSlotsAdjustmentLinkAsFinanceUser()
                .enterSlotsToBeAdjusted(Payments.VALID_PAYMENTS.slots)
                .selectPositiveAdjustment().selectReason("Refund").clickAdjustButton();
    }
    
    public static AdjustmentConfirmationPage loginAndCompleteManualNegativeAdjustmentOfSlotBalance(WebDriver driver, Login login, String aeRef) {
        return SearchForAePage.navigateHereFromLoginPage(driver, login)
                .searchForAeAndSubmit(aeRef).clickSlotsAdjustmentLinkAsFinanceUser()
                .enterSlotsToBeAdjusted(Payments.VALID_PAYMENTS.slots)
                .selectNegativeAdjustment().selectReason("Failed payment").clickAdjustButton();
    }

}
