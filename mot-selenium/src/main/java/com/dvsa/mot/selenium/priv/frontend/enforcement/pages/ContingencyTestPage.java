package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.enums.ContingencyReasons;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

import java.text.DecimalFormat;

import static com.dvsa.mot.selenium.datasource.Text.TEXT_OTHER_REASON;
import static com.dvsa.mot.selenium.datasource.enums.ContingencyReasons.*;

public class ContingencyTestPage extends BasePage {
    private final WebDriver driver;

    private static final DecimalFormat dayOrMonthFormatter = new DecimalFormat("00");

    public ContingencyTestPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        this.driver = driver;
    }

    @FindBy(id = "who-1") private WebElement testByYou;

    @FindBy(id = "who-2") private WebElement testByAnother;

    @FindBy(id = "ct-code") private WebElement contingencyCode;

    @FindBy(id = "type-1") private WebElement normalTestOption;

    @FindBy(id = "type-2") private WebElement retest;

    @FindBy(id = "dateTestDay") private WebElement day;

    @FindBy(id = "dateTestMonth") private WebElement month;

    @FindBy(id = "dateTestYear") private WebElement year;

    @FindBy(id = "radioOptionRadio-reason-groupSO") private WebElement systemOutage;

    @FindBy(id = "radioOptionRadio-reason-groupCP") private WebElement communication;

    @FindBy(id = "radioOptionRadio-reason-groupPI") private WebElement paymentIssue;

    @FindBy(id = "radioOptionRadio-reason-groupOT") private WebElement other;

    @FindBy(id = "otherReasons") private WebElement otherReasons;

    @FindBy(id = "confirm_ct_button") private WebElement btnConfirmContingency;

    @FindBy(id = "radio-site-group16") private WebElement selectASite;

    @FindBy(id = "vehicleRegistrationNumber") private WebElement vrm;

    public ContingencyTestPage enterContingencyCodes(String ctyCode) {

        contingencyCode.sendKeys(ctyCode);
        return this;
    }

    public static ContingencyTestPage navigateHereFromLoginPage(WebDriver driver, Login login) {

        UserDashboardPage.navigateHereFromLoginPage(driver, login).clickContingencyLink();
        return new ContingencyTestPage(driver);
    }

    public ContingencyTestPage testByYouMultiVTS(String siteGroupNum) {

        selectASite = driver.findElement(By.id("radio-site-group" + siteGroupNum));
        selectASite.click();
        return new ContingencyTestPage(driver);

    }

    public ContingencyTestPage enterDay(int day) {

        this.day.sendKeys(dayOrMonthFormatter.format(day));
        return this;
    }

    public ContingencyTestPage enterMonth(int month) {

        this.month.sendKeys(dayOrMonthFormatter.format(month));
        return this;
    }

    public ContingencyTestPage enterYear(int Year) {

        this.year.sendKeys(Integer.toString(Year));
        return this;

    }

    // Use this method to fill the Contingency Test Entry form
    public ContingencyTestPage fillContingencyTestEntryForm(Boolean normalTest, String ctyCode,
            int testDay, int testMonth, int testYear, ContingencyReasons reason) {

        // Enter Contingency code
        enterContingencyCodes(ctyCode);

        // Choose type of test
        if (normalTest) {

            normalTestOption.click();
        } else {

            retest.click();
        }

        // Enter the date that test was performed
        enterDay(testDay);
        enterMonth(testMonth);
        enterYear(testYear);

        // Enter the reason for contingency test
        if (reason == SYSTEM_OUTAGE) {

            systemOutage.click();
        } else if (reason == COMMUNICATION_PROBLEM) {

            communication.click();
        } else if (reason == PAYMENT_ISSUE) {

            paymentIssue.click();
        } else if (reason == OTHER) {

            other.click();
            otherReasons.sendKeys(TEXT_OTHER_REASON);
        }

        btnConfirmContingency.click();

        return new ContingencyTestPage(driver);


    }

    public static ContingencyTestPage navigateHereFromLoginPage(WebDriver driver, Login login,
            Vehicle vehicle) {
        return ContingencyTestPage.navigateHereFromLoginPage(driver, login, vehicle);
    }


    public String getCarReg() {

        return vrm.getText();
    }

}
