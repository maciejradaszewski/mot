package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.interactions.Actions;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;

public class MOTTestsPopupVerificationPage extends BasePage {

    @FindBy(id = "listMOTs") public WebElement vtsRecentTestResultsTable;
    //Page Elements
    @FindBy(id = "vts-search") private WebElement vtsSearch;

    @FindBy(id = "item-selector-btn-search") private WebElement search;

    @FindBy(xpath = "//div[1]/h1") private WebElement titleText;

    @FindBy(className = "popover fade right in") private WebElement popup;

    @FindBy(className = "info-popup") private WebElement resultText;

    @FindBy(id = "logout") private WebElement logout;

    @FindBy(xpath = "//div/div/h2") private WebElement testerTitle;

    @FindBy(className = "form-control") private WebElement filter;

    @FindBy(id = "summary") private WebElement summary;

    @FindBy(id = "type") private WebElement type;

    @FindBy(id = "userId") private WebElement userId;

    @FindBy(id = "reg") private WebElement reg;

    @FindBy(id = "site") private WebElement site;

    @FindBy(partialLinkText = "V1234") private WebElement siteLink;

    @FindBy(partialLinkText = "Return to results") private WebElement returnToResultsLink;

    @FindBy(id = "month1") private WebElement month1;

    @FindBy(id = "year1") private WebElement year1;

    @FindBy(partialLinkText = "View") private WebElement view;

    public MOTTestsPopupVerificationPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public void enterMonth1(int month) {
        String m = "" + month;
        month1.sendKeys(m);
    }

    public void clearMonth1() {
        waitForElementToBeVisible(month1, 5);
        month1.clear();
    }

    public void clearYear1() {
        waitForElementToBeVisible(year1, 5);
        year1.clear();
    }

    public void enterYear1(int year) {
        String m = "" + year;
        year1.sendKeys(m);
    }

    public void clickreturnToResultsLink() {
        returnToResultsLink.click();
    }

    public void clickSiteLink() {
        siteLink.click();
    }

    public void enterSearchText(String text) {
        vtsSearch.sendKeys(text);
    }

    public void search() {
        search.click();
    }

    public void waitForViewLink() {
        waitForElementToBeVisible(view, 5);
    }

    public String getTitle() {
        return titleText.getText();
    }

    public boolean verifyPopup() {
        try {
            return resultText.isDisplayed();
        } catch (Exception NoSuchElementException) {
            return false;
        }
    }

    public void selectType(String text) {
        Select dropDownBox = new Select(driver.findElement(By.id("type")));
        dropDownBox.selectByVisibleText(text);
    }

    public void logout() {
        logout.click();
    }

    public String getTesterTitle() {
        return testerTitle.getText();
    }

    public String getVRMTitle() {
        return testerTitle.getText();
    }

    public boolean verifySummaryColumn() {
        return summary.isDisplayed();
    }

    public boolean verifyRegistrationColumn() {
        return reg.isDisplayed();
    }

    public boolean verifyTypeColumn() {
        return type.isDisplayed();
    }

    public boolean verifyUserIdColumn() {
        return userId.isDisplayed();
    }

    public boolean verifysiteColumn() {
        return site.isDisplayed();
    }
}
