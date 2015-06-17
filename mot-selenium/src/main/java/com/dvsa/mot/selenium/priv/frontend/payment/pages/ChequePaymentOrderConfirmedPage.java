package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.datasource.ChequePayment;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.DetailsOfAuthorisedExaminerPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SearchForAePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class ChequePaymentOrderConfirmedPage extends BasePage {

    private static final String PAGE_TITLE = "ORDER CONFIRMED";

    @FindBy(id = "successMessage") private WebElement statusMessage;

    @FindBy(id = "totalOrdered") private WebElement totalSlotsOrdered;

    @FindBy(id = "totalCost") private WebElement totalCost;

    @FindBy(id = "purchaseDetails") private WebElement viewPurchaseDetailsLink;

    @FindBy(id = "cancelAndReturn") private WebElement returnToAeLink;

    public ChequePaymentOrderConfirmedPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getStatusMessage() {
        return statusMessage.getText();
    }

    public String getTotalSlotsOrdered() {
        return totalSlotsOrdered.getText();
    }

    public String getTotalCost() {
        return totalCost.getText();
    }

    public PaymentDetailsPage clickViewPurchaseDetailsLink() {
        viewPurchaseDetailsLink.click();
        return new PaymentDetailsPage(driver);
    }

    public static ChequePaymentOrderConfirmedPage purchaseSlotsByChequeSuccessfully(
            WebDriver driver, Login login, String aeReference, ChequePayment chequePayment) {
        return SearchForAePage.navigateHereFromLoginPage(driver, login)
                .searchForAeAndSubmit(aeReference).clickBuySlotsLinkAsFinanceUser()
                .selectChequePaymentType().clickStartOrder().enterChequeDetails(chequePayment)
                .clickCreateOrderButton().clickConfirmOrderButton();
    }

    public DetailsOfAuthorisedExaminerPage clickReturnToAeLink() {
        returnToAeLink.click();
        return new DetailsOfAuthorisedExaminerPage(driver);
    }

}
