package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.PageInteractionHelper;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SearchForAePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import java.util.List;

public class PaymentDetailsPage extends BasePage {

    private static final String PAGE_TITLE = "TRANSACTION DETAILS";

    @FindBy(id = "supplierDetails") private WebElement supplierDetails;

    @FindBy(id = "purchaserDetails") private WebElement purchaserDetails;

    @FindBy(id = "paymentDetails") private WebElement paymentReference;

    @FindBy(id = "receiptReference") private WebElement receiptNumber;

    @FindBy(id = "orderDetails") private WebElement orderDetails;

    @FindBy(id = "invoiceNumber") private WebElement invoiceNumber;

    @FindBy(id = "backToTransactionHistory") private WebElement backToTransactionHistoryLink;

    @FindBy(id = "print") private WebElement printButton;

    @FindBy(id = "startChargeback") private WebElement reverseThisPaymentButton;

    @FindBy(id = "statusMessage") private WebElement transactionStatusMessage;
    
    @FindBy(id = "startAdjustment") private WebElement adjustThePaymentButton;

    public PaymentDetailsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getSupplierDetailsText() {
        return supplierDetails.getText();
    }

    public String getPurchaserDetailsText() {
        return purchaserDetails.getText();
    }

    public String getPaymentDetailsText() {
        return paymentReference.getText();
    }

    public String getReceiptReference() {
        return receiptNumber.getText();
    }

    public String getOrderDetailsText() {
        return orderDetails.getText();
    }

    public String getInvoiceReference() {
        return invoiceNumber.getText();
    }

    public boolean isPrintButtonDisplayed() {
        return printButton.isDisplayed();
    }
    
    public ReasonForAdjustmentPage clickAdjustThePaymentButton() {
        adjustThePaymentButton.click();
        return new ReasonForAdjustmentPage(driver);
    }

    public boolean isReverseThisPaymentButtonDisplayed() {
        PageInteractionHelper interactionHelper = new PageInteractionHelper(driver);
        List<WebElement> reverseThisPaymentButton =
                interactionHelper.findElementWithoutImplicitWaits(By.id("startChargeback"));
        return (reverseThisPaymentButton.size() > 0);
    }

    public String getTransactionStatusMessage() {
        return transactionStatusMessage.getText();
    }

    public static PaymentDetailsPage navigateHereFromTransactionHistoryPage(WebDriver driver,
            Login login, String aeRef) {
        SearchForAePage.navigateHereFromLoginPage(driver, login).searchForAeAndSubmit(aeRef)
                .clickTransactionHistoryLink().clickFirstTransactionNumber();
        return new PaymentDetailsPage(driver);
    }

}
