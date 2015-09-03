package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.ChoosePaymentTypePage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.ManualAdjustmentOfSlotsPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.TransactionHistoryPage;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class DetailsOfAuthorisedExaminerPage extends BasePage {

    private static String PAGE_TITLE = "FULL DETAILS OF AUTHORISED EXAMINER";

    @FindBy(id = "event-history")
    private WebElement aeEventsHistoryLink;
    
    @FindBy(id = "add-slots")
    private WebElement buySlotsLink;
    
    @FindBy(id = "slots-adjustment")
    private WebElement slotsAdjustmentLink;
    
    @FindBy(id = "transaction-history")
    private WebElement transactionHistoryLink;
    
    @FindBy(id = "slot-count")
    private WebElement numberOfSlots;
    
    @FindBy(id = "slots-refund")
    private WebElement refundsLink;

    public DetailsOfAuthorisedExaminerPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public EventHistoryPage clickAeEventsHistoryLink() {
        aeEventsHistoryLink.click();
        return new EventHistoryPage(driver);
    }
    
    public ChoosePaymentTypePage clickBuySlotsLinkAsFinanceUser() {
        buySlotsLink.click();
        return new ChoosePaymentTypePage(driver);
    }
    
    public TransactionHistoryPage clickTransactionHistoryLink() {
        transactionHistoryLink.click();
        return new TransactionHistoryPage(driver);
    }
    
    public ManualAdjustmentOfSlotsPage clickSlotsAdjustmentLinkAsFinanceUser() {
        slotsAdjustmentLink.click();
        return new ManualAdjustmentOfSlotsPage(driver);
    }
    
    public String getAeSlotBalance() {
        return numberOfSlots.getText();
    }
}
