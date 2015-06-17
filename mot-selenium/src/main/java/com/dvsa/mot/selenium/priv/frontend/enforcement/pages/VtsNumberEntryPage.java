package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.BusinessDetails;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.interactions.Actions;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

import java.util.List;

//This page is the enforcement search screen for finding a vehicle testing station


public class VtsNumberEntryPage extends BasePage {

    @FindBy(id = "vts-search") private WebElement vtsField;

    @FindBy(id = "item-selector-btn-search") private WebElement vtsSearchButton;

    @FindBy(className = "tt-dropdown-menu") private WebElement dropDownBox;

    @FindBy(className = "tt-suggestions") private WebElement suggestionBox;

    @FindBy(id = "VehicleTestingStationSearch") private WebElement pageTitle;

    @FindBy(partialLinkText = "View") private WebElement view;

    @FindBy(id = "validation-summary-id") private WebElement validationSummary;

    public VtsNumberEntryPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        //checkTitle(ENFORCEMENT_VTS_FULL_SEARCH_PAGE.getPageTitle());
    }

    public static VtsNumberEntryPage navigateHereFromLoginPage(WebDriver driver) {
        driver.findElement(By.id("motTestingLink")).click();
        driver.findElement(By.id("search_vts")).click();
        return new VtsNumberEntryPage(driver);
    }

    public VtsNumberEntryPage enterVTSNumber(String text) {
        vtsField.sendKeys(text);
        waitForAjaxToComplete();
        return this;
    }

    public TestHistoryPage clickSearch() {
        vtsSearchButton.click();
        return new TestHistoryPage(driver);
    }

    public VtsNumberEntryPage clickSearchButtonExpectingError() {
        vtsSearchButton.click();
        return this;
    }

    public void waitForViewLink() {
        waitForElementToBeVisible(view, 5);
    }

    public EnforcementVTSSearchHistoryPage clickSearchExpectingEnforcementVTSsearchHistoryPage() {
        vtsSearchButton.click();
        return new EnforcementVTSSearchHistoryPage(driver);
    }

    public boolean pageTitleIsDisplayed() {
        return vtsField.isDisplayed();
    }

    public boolean suggestionsAreDisplayed() {
        waitForElementToBeVisible(suggestionBox, 5);
        return suggestionBox.isDisplayed();
    }

    public void selectSearchResultFromDropDown(BusinessDetails businessDetails) {
        List<WebElement> searchResults = driver.findElements(By.className("tt-suggestions"));

        //Click in search box to invoke intellisense
        suggestionBox.click();

        Actions action = new Actions(driver);

        //Loop through elements in the list to find a match on VTS number
        for (WebElement searchResult : searchResults) {
            if (searchResult.getText().toUpperCase()
                    .contains(businessDetails.BUSINESS_DETAILS_10.vtsNo.toUpperCase()))
                action.moveToElement(searchResult).clickAndHold(searchResult).release().build()
                        .perform();
            break;
        }
    }

    public String getErrorMessage(){
        return validationSummary.getText();
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
