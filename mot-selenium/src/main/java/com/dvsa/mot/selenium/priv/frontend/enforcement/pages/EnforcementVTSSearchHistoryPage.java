package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class EnforcementVTSSearchHistoryPage extends BasePage {


    public EnforcementVTSSearchHistoryPage(WebDriver driver) {
        super(driver);
        waitForAjaxToComplete();
        PageFactory.initElements(driver, this);
    }

    @FindBy(id = "listMOTs") public WebElement vtsRecentTestResultsTable;

    @FindBy(partialLinkText = "View") public WebElement summaryLink;

    @FindBy(id = "go-back") private WebElement goBackLink;

    @FindBy(id = "search-again") private WebElement searchAgain;

    @FindBy(className = "form-control") private WebElement filterBox;

    public boolean isResultsTableDisplayed() {
        return isElementDisplayed(vtsRecentTestResultsTable);
    }

    public void clickSummaryLink() {
        summaryLink.click();
    }

    public MotTestSummaryPage goToTestInProgressSummary(Login tester, Vehicle vehicle) {
        WebElement inProgressLink = driver.findElement(By.xpath(
                "(//table[@id='listMOTs']//tr[contains(.,'" + tester.username + "')])[contains(.,'"
                        + vehicle.carReg + "')]//a[text()='In progress']"));
        inProgressLink.click();
        return new MotTestSummaryPage(driver);
    }

    public MotTestSummaryPage gotToTestSummary(String motTestNumber, Vehicle vehicle) {
        filterBox.sendKeys(vehicle.carReg);
        WebElement summaryLink = driver.findElement(By.xpath("id('mot-" + motTestNumber + "')"));
        summaryLink.click();
        return new MotTestSummaryPage(driver);
    }

    public EnforcementVTSSearchPage clickOnGoBackLink() {
        goBackLink.click();
        return new EnforcementVTSSearchPage(driver);
    }

    public EnforcementVTSSearchPage clickOnSearchAgain() {
        searchAgain.click();
        return new EnforcementVTSSearchPage(driver);
    }
}

