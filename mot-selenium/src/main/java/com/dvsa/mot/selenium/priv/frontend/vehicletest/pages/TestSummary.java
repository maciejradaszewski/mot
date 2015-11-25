package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfigurationPageField;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResultsPageField;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.Utilities;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EnforcementReInspectionTestCompletePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

import java.util.Map;

public class TestSummary extends BasePage {
    @FindBy(id = "back-to-home-link") private WebElement reprintCertificate;

    @FindBy(id = "testStatus") private WebElement testStatus;

    @FindBy(id = "expiryDate") private WebElement expiryDate;

    @FindBy(id = "oneTimePassword") private WebElement enterPasscode;

    @FindBy(id = "confirm_test_result") protected WebElement finishAndPrintButton;

    @FindBy(id = "cancel_test_result") private WebElement backButton;

    @FindBy(id = "motTestNumber") private WebElement motTestNumber;

    @FindBy(id = "registrationNumber") private WebElement registrationNumber;

    @FindBy(id = "issueDate") private WebElement issueDate;

    @FindBy(id = "vinChassisNumber") private WebElement vin;

    @FindBy(id = "testClass") private WebElement testClass;

    @FindBy(id = "aproxFirstUse") private WebElement dateFirstUsed;

    @FindBy(id = "make") private WebElement make;

    @FindBy(id = "model") private WebElement model;

    @FindBy(id = "colour") private WebElement colour;

    @FindBy(id = "odometerReading") private WebElement odometerReading;

    @FindBy(className = "primary-item") //Brake Results
    private WebElement brakeResults;

    @FindBy(id = "prsNoneRecorded") private WebElement prsNoneRecorded;

    @FindBy(id = "advisoryNoneRecorded") private WebElement advisoryText;

    @FindBy(xpath = "//form[@id='submit-test-results']//a[text()='Report a faulty card']")
    private WebElement reportFaultyCardLink;

    @FindBy(id = "validation-summary-id") private WebElement validationSummary;

    @FindBy(id = "otpErrorMessage") private WebElement otpErrorMessage;

    @FindBy(id = "otpErrorMessageDescription") private WebElement otpErrorMessageDescription;

    @FindBy(id = "fails") private WebElement rfrDetails;

    @FindBy(id = "prses") private WebElement prsDetails;

    @FindBy(id = "reprintDialog") private WebElement reprintDialog;

    @FindBy(xpath = "//div[@id='reprintDialog']/div[2]/a") private WebElement goToHomepage;

    @FindBy(id = "logout") private WebElement logout;

    @FindBy(id = "change-location") private WebElement changeLocation;

    public TestSummary(WebDriver driver) {
        super(driver);
    }

    public static TestSummary navigateHereFromLoginPage(WebDriver driver, Login login,
            Vehicle vehicle, String odometerValue,
            Map<BrakeTestConfigurationPageField, Object> configEntries,
            Map<BrakeTestResultsPageField, Object> brakeTestEntries, FailureRejection[] failures,
            PRSrejection[] prs, AdvisoryRejection[] advisories, ManualAdvisory[] manualAdvisories) {
        return MotTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .addMotTest(odometerValue, configEntries, brakeTestEntries, failures, prs,
                        advisories, manualAdvisories).createCertificate();
    }

    /**
     * Get MOT test status
     *
     * @return string with status
     */
    public String getTestStatus() {

        if (!isElementPresent(By.id("testStatus"))) {

            testStatus = new WebDriverWait(driver, 1)
                    .until(ExpectedConditions.visibilityOf(findWebElement(By.id("testStatus"))));
        }

        return testStatus.getText();
    }

    public TestSummary enterNewPasscode(String passcode) {
        enterPasscode.sendKeys(passcode);
        return this;
    }

    public MOTTestResultPageTestCompletePage clickFinishPrint() {
        disablePrintingOnCurrentPage();

        finishAndPrintButton.click();
        return new MOTTestResultPageTestCompletePage(driver);
    }

    public String getPrintCertificateUrl() {
        return reprintCertificate.getAttribute("href");
    }

    public MOTTestResultPageTestCompletePage clickFinishPrint(String passcode) {
        enterNewPasscode(passcode);
        disablePrintingOnCurrentPage();
        finishAndPrintButton.click();
        return new MOTTestResultPageTestCompletePage(driver);
    }

    public MOTTestResultPageTestCompletePage clickFinishPrint(String passcode, String pageTitle) {
        enterNewPasscode(passcode);
        disablePrintingOnCurrentPage();
        finishAndPrintButton.click();
        return new MOTTestResultPageTestCompletePage(driver, pageTitle);
    }

    public TestSummary clickFinishPrintExpectingError() {
        disablePrintingOnCurrentPage();
        finishAndPrintButton.click();
        return this;
    }

    public EnforcementReInspectionTestCompletePage clickFinishPrintReinspection() {

        finishAndPrintButton.click();
        return new EnforcementReInspectionTestCompletePage(driver);
    }

    public boolean printDocButtonExist() {

        if (reprintCertificate.isDisplayed()) {
            return true;
        } else
            return false;
    }

    public String getVin() {
        return vin.getText();
    }

    public String getMotTestNumber() {
        return motTestNumber.getText();
    }

    public String getRegNumber() {
        return registrationNumber.getText();
    }

    public String getTestClass() {
        return testClass.getText();
    }

    //Get Odometer reading- strip out ",
    public String getOdometerReading() {
        return odometerReading.getText().replace("miles", "").replace(",", "").trim();
    }

    public String getPrs() {
        return prsNoneRecorded.getText();

    }

    public String getMake() {
        return make.getText();
    }

    public String getModel() {
        return model.getText();
    }

    public String getColour() {
        return colour.getText();
    }

    public String getRfrDetails() {
        return rfrDetails.getText();
    }

    public String getPrsDetails() {
        return prsDetails.getText();
    }

    public boolean motTestStatus() {

        if (getTestStatus().equalsIgnoreCase("PASS")) {
            return true;
        } else if (getTestStatus().equalsIgnoreCase("Fail")) {
            return true;
        }
        return false;
    }

    public String generateNewVT30FileName() {
        return "VT30" + Utilities.getSystemDateAndTime();
    }

    public String generateNewVT20FileName() {
        return "VT20" + Utilities.getSystemDateAndTime();
    }

    public String generateNewVT32FileName() {
        return "VT32" + Utilities.getSystemDateAndTime();
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }

}
