package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.AbortMotTestPage;
import org.joda.time.DateTime;
import org.joda.time.Period;
import org.joda.time.format.DateTimeFormat;
import org.joda.time.format.DateTimeFormatter;
import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.CacheLookup;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;

public class MotTestSummaryPage extends BasePage {

    public MotTestSummaryPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    @FindBy(id = "siteidentry") private WebElement siteId;

    @FindBy(id = "location") private WebElement siteLocation;

    @FindBy(className = "tt-hint") private WebElement suggestionBox;

    @FindBy(xpath = "html/body/div[3]/form/div[4]/div[2]/div/span/input[1]") private WebElement
            siteNumberFirstElement;

    @FindBy(id = "confirm_test_result") private WebElement finishtest;

    @FindBy(id = "compareTestResults") private WebElement compareTestResults;

    @FindBy(id = "record_assessment_button") private WebElement recordAssessment;

    @FindBy(id = "hdr-col3-dt2") private WebElement reInspecLocationLabel;

    @FindBy(id = "hdr-col3-dd2") private WebElement reInspeLocationData;

    @FindBy(id = "hdr-col3-dt2") private WebElement reInspecSiteLocationLabel;

    @FindBy(id = "hdr-col3-dd2") private WebElement reInspeSiteLocationData;

    @FindBy(xpath = "/html/body/div[2]/div[4]/div/div/form/table/tbody/tr[2]/td[5]/textarea")
    private WebElement justification;

    @FindBy(id = "finalJustification") private WebElement finaljustificaiton;

    @FindBy(id = "onePersonTest") private WebElement onePersonTest;

    @FindBy(id = "onePersonReInspection") private WebElement onePersonReInspection;

    @FindBy(id = "veh-col2-dt2") private WebElement OnepersonReinspecLabel;

    @FindBy(id = "veh-col2-dd2") private WebElement OnepersonReinspecdata;

    @FindBy(id = "veh-col1-dt2") private WebElement OnepersonTestLabel;

    @FindBy(id = "veh-col1-dd2") private WebElement OnepersonTestdata;

    @FindBy(id = "testStatus") private WebElement testStatus;

    @FindBy(id = "abort_test_button") private WebElement abortTest;

    @FindBy(id = "logout") @CacheLookup private WebElement logout;

    @FindBy(id = "testClass") private WebElement testClass;

    @Deprecated //Use LoginPage clickLogout() method in BasePage instead
    public void logout() {
        logout.click();
    }

    @FindBy(id = "expiryDate") private WebElement expiryDate;

    @FindBy(id = "issueDate") private WebElement issueDate;

    @FindBy(id = "motTestNumber") private WebElement motTestNumber;

    @FindBy(xpath = "//a[text()='Print certificate']") @CacheLookup private WebElement
            printCertificateButton;

    public MotTestSummaryPage enterSearchCriteria(String text) {
        siteId.sendKeys(text);
        return new MotTestSummaryPage(driver);
    }

    public boolean isLocationEnabled() {
        return siteLocation.isEnabled();
    }

    public String getTestClass() {
        return testClass.getText();
    }

    public boolean isSitedisabled() {

        boolean result = false;
        if (siteId.isEnabled() == false)
            result = true;
        else
            result = false;
        return result;
    }

    public MotTestSummaryPage enterLocation(String location) {
        siteLocation.sendKeys(location);
        return new MotTestSummaryPage(driver);
    }

    public EnforcementReInspectionTestCompletePage clickFinishTest() {

        finishtest.click();
        return new EnforcementReInspectionTestCompletePage(driver);
    }

    public void clickCompareResults() {

        compareTestResults.click();

    }


    public String getTestStatus() {
        return testStatus.getText();
    }

    public AbortMotTestPage abortTest() {
        abortTest.click();
        return new AbortMotTestPage(driver);
    }

    public boolean verifyexpiryDate() {
        try {
            return expiryDate.isDisplayed();
        } catch (Exception NoSuchElementException) {
            return false;
        }
    }

    public String getExpiryDate() {
        return expiryDate.getText();
    }

    public String getIssueDate() {
        return issueDate.getText();
    }

    public String getExpiryDateFromIssueDate() {
        DateTimeFormatter dateTimeFormatter = DateTimeFormat.forPattern("d MMMM YYYY");
        DateTime dateTime = DateTime.parse(getIssueDate(), dateTimeFormatter);
        dateTime = dateTime.plus(Period.years(1)).minus(Period.days(1));
        return dateTime.toString(dateTimeFormatter);
    }

    public String getMotTestNumber() {
        return motTestNumber.getText();
    }

    /**
     * Test that the Print certificate button exists.
     *
     * @return
     */
    public boolean printCertificateButtonExists() {

        try {
            printCertificateButton.isDisplayed();
        } catch (NoSuchElementException e) {
            return false;
        }

        return true;
    }

    public AbortMotTestPage clickAbortTest() {
        abortTest.click();
        return new AbortMotTestPage(driver);
    }
}
