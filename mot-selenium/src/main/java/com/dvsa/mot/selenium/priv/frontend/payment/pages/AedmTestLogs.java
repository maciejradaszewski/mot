package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class AedmTestLogs extends BasePage {

    public static final String PAGE_TITLE = "TEST LOGS OF AUTHORISED EXAMINER\n"
            + "TEST ORGANISATION";

    @FindBy(id = "dateFrom-Day") private WebElement fromWhichDay;

    @FindBy(id = "dateFrom-Month") private WebElement fromWhichMonth;

    @FindBy(id = "dateFrom-Year") private WebElement fromWhichYear;

    @FindBy(id = "dateTo-Day") private WebElement toWhichDay;

    @FindBy(id = "dateTo-Month") private WebElement toWhichMonth;

    @FindBy(id = "dateTo-Year") private WebElement toWhichYear;

    @FindBy(id = "btn_search") private WebElement downloadCsvReport;

    @FindBy(id = "validation-message--failure") private WebElement errorMessage;

    public AedmTestLogs(WebDriver driver, String title) {
        super(driver);
        checkTitle(PAGE_TITLE + " " + title);
        PageFactory.initElements(driver, this);
    }

    public void setFromWhichDay(String day) {
        fromWhichDay.clear();
        fromWhichDay.sendKeys(day);
    }

    public void setFromWhichMonth(String month) {
        fromWhichMonth.clear();
        fromWhichMonth.sendKeys(month);
    }

    public void setFromWhichYear(String year) {
        fromWhichYear.clear();
        fromWhichYear.sendKeys(year);
    }

    public void setToWhichDay(String day) {
        toWhichDay.clear();
        toWhichDay.sendKeys(day);
    }

    public void setToWhichMonth(String month) {
        toWhichMonth.clear();
        toWhichMonth.sendKeys(month);
    }

    public void setToWhichYear(String year) {
        toWhichYear.clear();
        toWhichYear.sendKeys(year);
    }

    public AedmTestLogs downloadCsvReport() {
        downloadCsvReport.click();
        return this;
    }

    public boolean isValidationSummaryDisplayed(){
        return errorMessage.isDisplayed();
    }

}
