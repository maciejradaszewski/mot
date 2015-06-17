package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Class4MOTData;
import com.dvsa.mot.selenium.datasource.Class4MOTData.Class4MOTConfiguration;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.interactions.Actions;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;


public class VtsRecentResultsPage extends BasePage {

    @FindBy(id = "listMOTs") public WebElement vtsRecentTestResultsTable;

    @FindBy(className = "col-sm-12") public WebElement garageName;

    @FindBy(partialLinkText = "search again") public WebElement searchAgain;

    @FindBy(className = "info-popup") public WebElement shortSummary;

    @FindBy(partialLinkText = "View") public WebElement View;

    @FindBy(id = "result") private WebElement resultColumn;

    @FindBy(className = "form-control") private WebElement filterText;


    public VtsRecentResultsPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public boolean isResultsTableDisplayed() {
        waitForElementToBeVisible(vtsRecentTestResultsTable, 5);
        return (vtsRecentTestResultsTable.isDisplayed());
    }

    public void selectSummaryLinkFromTable() {
        View.click();
    }

    public void selectSummaryLinkFromTable(Login login, Vehicle vehicle) {

        waitForElementToBeVisible(vtsRecentTestResultsTable, 5);
        WebElement viewLink = driver.findElement(By.xpath(
                "(//*[@id='listMOTs']//tr[contains(.,'" + login.username
                        + "')])//a[text()='View']"));
        viewLink.click();
    }

    //Click on the summary link to show the summary screen
    public boolean hoverOverResultLinkFromTable(String result, Vehicle carDetails,
            Class4MOTConfiguration motDetails) {
        boolean textCheck = false;
        if (result == Class4MOTData.MotResultState.PASS) {
            //Check pass criteria
            driver.findElement(By.linkText(result)).click();
            new Actions(driver).moveToElement(driver.findElement(By.linkText(result))).click()
                    .perform();
            textCheck = true;
        } else if (result == Class4MOTData.MotResultState.FAIL) {

            //Check fail criteria
            driver.findElement(By.linkText(result)).click();
            new Actions(driver).moveToElement(driver.findElement(By.linkText(result))).click()
                    .perform();
            textCheck = true;
        } else if (result == Class4MOTData.MotResultState.IN_PROGRESS) {
            //Check in progress criteria
            driver.findElement(By.linkText(result)).click();
            textCheck = true;
        } else {
            //Assert fail
            textCheck = false;
        }
        return textCheck;
    }

    public void clickSearchAgain() {
        searchAgain.click();
    }

    public void selectFilter(String text) {
        filterText.sendKeys(text);
    }

    public String getMotTestStatus(Login login, Vehicle vehicle) {
        waitForTextToBePresentInElement(vtsRecentTestResultsTable, vehicle.carReg, 20);
        WebElement resultLink = driver.findElement(By.xpath(
                "((//*[@id='listMOTs']//tr[contains(.,'" + login.username + "')])[contains(.,'"
                        + vehicle.carReg + "')])//a[1]"));
        System.out.println(resultLink.getText());
        return resultLink.getText();
    }

    public String getMotTestStatusRegSearch(Login login, Vehicle vehicle) {
        waitForElementToBeVisible(vtsRecentTestResultsTable, 5);
        WebElement resultLink = vtsRecentTestResultsTable.findElement(By.xpath(
                "((//tr[contains(.,'" + login.username + "')])[contains(.,'" + vehicle.fullVIN
                        + "')])//a[1]"));
        System.out.println(resultLink.getText());
        return resultLink.getText();
    }
}
