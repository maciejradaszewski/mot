package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.PageInteractionHelper;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import java.util.List;

public class TransactionHistoryPage extends BasePage {

    private static final String PAGE_TITLE = "PURCHASE HISTORY";

    @FindBy(id = "summaryLine") private WebElement numberOfTransactions;

    @FindBy(id = "today") private WebElement todayTransactionsLink;

    @FindBy(id = "last7Days") private WebElement last7DaysTransactionsLink;

    @FindBy(id = "last30Days") private WebElement last30DaysTransactionsLink;

    @FindBy(id = "lastYear") private WebElement lastYearTransactionsLink;

    @FindBy(id = "transactionHistoryTable") private WebElement transactionsTable;

    @FindBy(id = "downloadFile") private WebElement downloadFiles;

    @FindBy(xpath = "id('transactionHistoryTable')/tbody/tr/td[1]/a") private WebElement
            firstTransactionNumber;

    public TransactionHistoryPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public boolean isNumberOfTransactionsDisplayed() {
        return numberOfTransactions.isDisplayed();
    }

    public String getNumberOfTransactionsText() {
        return numberOfTransactions.getText();
    }

    public boolean isTransactionsTableDisplayed() {
        PageInteractionHelper interactionHelper = new PageInteractionHelper(driver);
        List<WebElement> table =
                interactionHelper.findElementWithoutImplicitWaits(By.id("transactionHistoryTable"));
        return (table.size() > 0);
    }

    public boolean isDownloadFileOptionsDisplayed() {
        return downloadFiles.isDisplayed();
    }

    public TransactionHistoryPage clickTodayTransactionsLink() {
        todayTransactionsLink.click();
        return new TransactionHistoryPage(driver);
    }

    public TransactionHistoryPage clickLast7DaysTransactionsLink() {
        last7DaysTransactionsLink.click();
        return new TransactionHistoryPage(driver);
    }

    public TransactionHistoryPage clickLast30DaysTransactionsLink() {
        last30DaysTransactionsLink.click();
        return new TransactionHistoryPage(driver);
    }

    public TransactionHistoryPage clickLastYearTransactionsLink() {
        lastYearTransactionsLink.click();
        return new TransactionHistoryPage(driver);
    }

    public PaymentDetailsPage clickFirstTransactionNumber() {
        firstTransactionNumber.click();
        return new PaymentDetailsPage(driver);
    }

}
