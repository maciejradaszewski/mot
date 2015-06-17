package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class FinancialReportDownloadPage extends BasePage {

    @FindBy(id = "backToGenerateReport") private WebElement backToGenerateReport;

    public FinancialReportDownloadPage(WebDriver driver, String partialTitle) {
        super(driver);
        checkTitle(partialTitle);
    }

    public boolean isBackToGenerateReportLinkDisplayed() {
        return isElementDisplayed(backToGenerateReport);
    }

}
