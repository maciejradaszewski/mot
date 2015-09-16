package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import com.dvsa.mot.selenium.datasource.ChequePayment;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;

public class ManualAdjustmentSuccessPage extends BasePage {
    
    private static final String PAGE_TITLE = "MANUAL ADJUSTMENT";
    
    @FindBy(id = "statusMessage") private WebElement statusMessage;

    public ManualAdjustmentSuccessPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }
    
    public String getAdjustmentStatusMessage() {
        return statusMessage.getText();
    }
    
    public static ManualAdjustmentSuccessPage loginAndAdjustPaymentForWrongAe(WebDriver driver, Login login, String aeRef1, ChequePayment chequePayment, String aeRef2) {
        return ChequePaymentOrderConfirmedPage.purchaseSlotsByChequeSuccessfully(driver, login, aeRef1, chequePayment)
                .clickViewPurchaseDetailsLink()
                .clickAdjustThePaymentButton()
                .selectReasonForAdjustment("Incorrect Customer allocated")
                .clickContinueButton()
                .enterAeNumber(aeRef2)
                .clickCreateOrderButton()
                .clickConfirmAdjustment();
    }
    
    public static ManualAdjustmentSuccessPage loginAndAdjustPaymentForInvalidPaymentData(WebDriver driver, Login login, String aeRef1, ChequePayment chequePayment, String amount) {
        return ChequePaymentOrderConfirmedPage.purchaseSlotsByChequeSuccessfully(driver, login, aeRef1, chequePayment)
                .clickViewPurchaseDetailsLink()
                .clickAdjustThePaymentButton()
                .selectReasonForAdjustment("Incorrect Amount input")
                .clickContinueButton()
                .enterAdjustmentAmount(amount)
                .clickCreateOrderButton()
                .clickConfirmAdjustment();
    }

}
