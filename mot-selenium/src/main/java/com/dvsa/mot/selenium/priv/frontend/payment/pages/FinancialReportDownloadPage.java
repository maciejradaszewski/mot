package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;

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
    
    public static FinancialReportDownloadPage navigateHereFromLoginAndGenerateFinancialReports(WebDriver driver, Login login, String reportType,
            String reportTitle) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login)
                .clickGeneralFinancialReportsLink().selectReportType(reportType)
                .clickGenerateReportButton(reportTitle);
    }

}
