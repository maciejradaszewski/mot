package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;

public class FinancialReportsPage extends BasePage {

    private static final String PAGE_TITLE = "GENERATE A REPORT";

    @FindBy(id = "generateReport") private WebElement generateReportButton;

    public FinancialReportsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public FinancialReportsPage selectReportType(String reportType) {
        Select dropDownBox = new Select(driver.findElement(By.id("input_report_type")));
        dropDownBox.selectByVisibleText(reportType);
        return new FinancialReportsPage(driver);
    }

    public FinancialReportDownloadPage clickGenerateReportButton(String reportTitle) {
        generateReportButton.click();
        return new FinancialReportDownloadPage(driver, reportTitle.toUpperCase());
    }

}
