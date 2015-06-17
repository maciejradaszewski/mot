package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.enums.MotSearchBy;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.joda.time.DateTime;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;

public class EnforcementVTSSearchPage extends BasePage {

    @FindBy(id = "vts-search") private WebElement vtsSearch;

    @FindBy(className = "tt-suggestions") private WebElement suggestionBox;

    @FindBy(partialLinkText = "search again") private WebElement searchAgain;

    @FindBy(id = "validationErrors") private WebElement validationErrorMessage;

    @FindBy(id = "listMOTs") private WebElement motSearchResults;

    @FindBy(id = "item-selector-btn-search") private WebElement searchButton;

    @FindBy(className = "tt-dropdown-menu") private WebElement autoSearchResultDropdown;

    @FindBy(id = "type") private WebElement searchForMotTestByDropdown;

    @FindBy(id = "validationErrors") private WebElement searchValidationErrorsFeedback;

    @FindBy(id = "month1") private WebElement startMonth;

    @FindBy(id = "year1") private WebElement startYear;

    @FindBy(id = "month2") private WebElement endMonth;

    @FindBy(id = "year2") private WebElement endYear;

    @FindBy(partialLinkText = "View") private WebElement view;

    @FindBy(id = "feedback-link") private WebElement feedbackLink;

    public EnforcementVTSSearchPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public void waitForViewLink() {
        waitForElementToBeVisible(view, 5);
    }

    public static EnforcementVTSSearchPage navigateHereFromLoginPage(WebDriver driver,
            Login login) {
        return EnforcementHomePage.navigateHereFromLoginPage(driver, login).clickMOTLink();
    }

    public EnforcementVTSSearchPage selectDropdown(String text) {
        Select dropDownBox = new Select(driver.findElement(By.id("type")));
        dropDownBox.selectByVisibleText(text);
        return this;

    }

    public EnforcementVTSSearchPage selectMotSearchBy(MotSearchBy motSearchBy) {
        Select selectMotSearchBy = new Select(searchForMotTestByDropdown);
        selectMotSearchBy.selectByValue(motSearchBy.getMotTestTypeId());
        return this;
    }

    public TestHistoryPage searchForVehicle(String vehicle) {
        vtsSearch.sendKeys(vehicle);
        searchButton.click();
        return new TestHistoryPage(driver);
    }

    public String getValidationTextValue() {
        //validationErrors
        return searchValidationErrorsFeedback.getText();
    }

    public EnforcementVTSSearchPage enterStartMonth(String month) {
        waitForElementToBeVisible(startMonth, 5);
        startMonth.sendKeys(month);
        return this;
    }

    public EnforcementVTSSearchPage enterStartYear(String year) {
        waitForElementToBeVisible(startYear, 5);
        startYear.sendKeys(year);
        return this;
    }

    public EnforcementVTSSearchPage enterEndMonth(String month) {
        waitForElementToBeVisible(endMonth, 5);
        endMonth.sendKeys(month);
        return this;
    }

    public EnforcementVTSSearchPage enterEndYear(String year) {
        waitForElementToBeVisible(endYear, 5);
        endYear.sendKeys(year);
        return this;
    }

    public EnforcementVTSSearchPage clearFieldStartMonth() {
        waitForElementToBeVisible(startMonth, 5);
        startMonth.clear();
        return this;
    }

    public EnforcementVTSSearchPage clearFieldEndMonth() {
        waitForElementToBeVisible(endMonth, 5);
        endMonth.clear();
        return this;
    }

    public EnforcementVTSSearchPage clearFieldStartYear() {
        waitForElementToBeVisible(startYear, 5);
        startYear.clear();
        return this;
    }

    public EnforcementVTSSearchPage clearFieldEndYear() {
        waitForElementToBeVisible(endYear, 5);
        endYear.clear();
        return this;
    }

    public EnforcementVTSSearchPage vtsByValidDateRange() {
        DateTime dt = new DateTime();

        clearFieldStartMonth();
        clearFieldStartYear();
        enterStartMonth("04");
        enterStartYear("2012");
        clearFieldEndMonth();
        clearFieldEndYear();
        enterEndMonth(String.valueOf(dt.getMonthOfYear()));
        enterEndYear(String.valueOf(dt.getYear()));
        return this;
    }

    public EnforcementVTSSearchPage vtsByDateRangeInvalid() {
        clearFieldStartMonth();
        clearFieldStartYear();
        enterStartMonth("04");
        enterStartYear("2012");
        clearFieldEndMonth();
        clearFieldEndYear();
        enterEndMonth("05");
        enterEndYear("2014");
        enterSearchCriteria("9090");
        clickSearch();
        return this;
    }

    public EnforcementVTSSearchPage vtsByDateRangeNull() {
        clearFieldStartMonth();
        clearFieldStartYear();
        enterStartMonth("04");
        enterStartYear("2012");
        clearFieldEndMonth();
        clearFieldEndYear();
        enterEndMonth("05");
        enterEndYear("2014");
        enterSearchCriteria(" ");
        clickSearch();
        return this;
    }

    public EnforcementVTSSearchPage enterSearchCriteria(String text) {
        vtsSearch.clear();
        vtsSearch.sendKeys(text);
        return this;
    }

    public EnforcementVTSSearchHistoryPage clickSearch() {
        searchButton.click();
        return new EnforcementVTSSearchHistoryPage(driver);
    }

    public String getFeedbackLink() {
        return feedbackLink.getAttribute("href");
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
