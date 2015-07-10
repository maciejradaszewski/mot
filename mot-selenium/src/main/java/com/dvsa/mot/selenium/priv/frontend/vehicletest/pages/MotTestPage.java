package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfigurationPageField;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResultsPageField;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest.pages.BrakeTestConfigurationPage;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;

import java.util.Map;

public class MotTestPage extends BasePage {

    private static final String DEFAULT_PAGE_TITLE = "MOT TEST RESULTS ENTRY";

    @FindBy(id = "odometer_submit") protected WebElement odometerSubmit;

    @FindBy(id = "createCertificate") protected WebElement createCertificate;

    @FindBy(className = "active") private WebElement stepInfo;

    @FindBy(tagName = "h1") private WebElement stepTitle;

    @FindBy(className = "col-md-8") private WebElement carNameAndYear;

    @FindBy(id = "cancelMotTest") private WebElement cancelMotTest;

    @FindBy(id = "vehicle-summary-more") private WebElement carYear;

    @FindBy(id = "toggle-details") private WebElement toggleDetails;

    @FindBy(name = "unit") private WebElement odometerUnit;

    @FindBy(id = "odometer") private WebElement odometerField;

    @FindBy(id = "addOdometer") private WebElement odometerUpdateButton;

    @FindBy(id = "info-message") private WebElement infoMessage;

    @FindBy(id = "add_rfr_button") private WebElement AddRFRButton;

    @FindBy(id = "reasonsForRejection") private WebElement reasonsForRejection;

    @FindBy(id = "failureCount") private WebElement numberOfFailures;

    @FindBy(id = "failureResults") private WebElement failureResults;

    @FindBy(id = "prsCount") private WebElement numberOfPRS;

    @FindBy(id = "prsResults") private WebElement prsResults;

    @FindBy(id = "advisoryCount") private WebElement numberOfAdvisories;

    @FindBy(id = "advisoryResults") private WebElement advisoryResults;

    @FindBy(id = "odometerReading") private WebElement odometerReading;

    @FindBy(id = "addBrakeTestResults") private WebElement addBrakeTest;

    @FindBy(id = "brakeTestResult") private WebElement brakeTestResult;

    @FindBy(id = "viewBrakeTestResults") private WebElement viewBrakeTestResults;

    @FindBy(id = "odometerReadingNotice") private WebElement odometerReadingNotice;

    @FindBy(id = "brakeTestResultsNotice") private WebElement brakeTestResultsNotice;

    @FindBy(id = "reason-for-rejection-modal") private WebElement viewReasonsForRejectionsLink;

    @FindBy(id = "quit") private WebElement motDone;

    @FindBy(id = "rfr-modal-close") private WebElement closeRFRList;

    @FindBy(id = "rfr-remove") private WebElement removeRfR;

    @FindBy(id = "vehicle-summary-more") private WebElement vehicleSummaryMore;

    @FindBy(linkText = "Edit") private WebElement editButton;

    @FindBy(id = "vtsNameAndAddress") private WebElement inspectionLocation;

    public MotTestPage(WebDriver driver) {
        this(driver, DEFAULT_PAGE_TITLE);
    }

    public MotTestPage(WebDriver driver, String title) {
        super(driver);
        checkTitle(title);
    }

    public static MotTestPage navigateHereFromLoginPage(WebDriver driver, Login login,
            Vehicle vehicle) {
        return MotTestStartedPage.navigateHereFromLoginPage(driver, login, vehicle).returnToHome()
                .resumeMotTest();
    }

    public static MotTestPage navigateHereFromLoginPage(WebDriver driver, Login login,
            Vehicle vehicle, Site site) {

        return MotTestStartedPage.navigateHereFromLoginPage(driver, login, vehicle, site)
                .returnToHome().resumeMotTest();
    }


    public ReasonsToCancelPage clickCancelMotTest() {
        cancelMotTest.click();
        return new ReasonsToCancelPage(driver);
    }

    public UserDashboardPage cancelMotTest(ReasonToCancel reason) {
        return clickCancelMotTest().enterAndSubmitReasonsToCancelPageExpectingAbortedPage(reason)
                .clickFinish();
    }

    public String getCarName() {
        return carNameAndYear.getText();
    }

    public String getCarYear() {
        return carYear.getText();
    }



    public String getDisplayedOdometerReading() {
        return odometerReading.getText().replace("Odometer reading\n", "").replace("\nUpdate", "");
    }

    public String getSelectedOdometerUnit() {
        return new Select(odometerUnit).getFirstSelectedOption().getText().trim();
    }

    public void setOdometerUnit(String unit) {
        if (unit == "mi" || unit == "km") {
            setUnit(unit);
            return;
        }
        throw new RuntimeException("Wrong Odometer unit provided!");
    }

    private void setUnit(String unit) {
        new Select(odometerUnit).selectByValue(unit);
    }

    public MotTestPage enterOdometerValues(String odometerValue) {
        clickUpdateOdometer();
        typeOdometer(odometerValue);
        return this;
    }

    public MotTestPage enterOdometerValuesAndSubmit(String odometerValue) {
        enterOdometerValues(odometerValue);
        submitOdometer();
        return new MotTestPage(driver);
    }

    public MotTestPage enterOdometerValuesAndSubmit(String odometerValue, String title) {
        enterOdometerValues(odometerValue);
        submitOdometer(title);
        return new MotTestPage(driver, title);
    }

    public MotTestPage enterOdometerValuesAndSubmit(int unit) {
        return enterOdometerValuesAndSubmit(String.valueOf(unit));
    }

    public MotTestPage enterOdometerValuesAndUnit(String odometerValue, String unit) {
        enterOdometerValues(odometerValue);
        setOdometerUnit(unit);
        submitOdometer();
        return new MotTestPage(driver);
    }

    public void typeOdometer(String text) {
        odometerField.sendKeys(text);
    }

    public MotTestPage clickMoreDetails() {
        toggleDetails.click();
        waitForTextToBePresentInElement(toggleDetails, "less", 10);
        return this;
    }

    public String getVehicleDetailsInfo() {
        clickMoreDetails();
        return vehicleSummaryMore.getText();
    }

    public MotTestPage clickUpdateOdometer() {
        odometerUpdateButton.click();
        waitForElementToBeVisible(odometerSubmit, 1);
        return this;
    }

    public MotTestPage submitOdometer() {
        odometerSubmit.click();
        return new MotTestPage(driver);
    }

    public MotTestPage submitOdometer(String title) {
        odometerSubmit.click();
        return new MotTestPage(driver, title);
    }

    public String getInfoMessage() {
        return infoMessage.getText();
    }

    public ReasonForRejectionPage addRFR() {
        AddRFRButton.click();
        return new ReasonForRejectionPage(driver);
    }

    public int getNumberOfFailures() {
        try {
            if (isElementDisplayed(numberOfFailures)) {
                String failuresText = numberOfFailures.getText();
                String strNumber =
                        failuresText.substring(0, failuresText.toLowerCase().indexOf("failure"))
                                .trim();
                return Integer.parseInt(strNumber);
            } else {
                return 0;
            }
        } catch (NumberFormatException e) {
            return -1;
        } catch (NoSuchElementException ne) {
            return 0;
        }
    }

    public int getNumberOfPRS() {
        try {
            if (isElementDisplayed(numberOfPRS)) {
                String PRSText = numberOfPRS.getText();
                String strNumber =
                        PRSText.substring(0, PRSText.toUpperCase().indexOf("PRS")).trim();
                return Integer.parseInt(strNumber);
            } else {
                return 0;
            }
        } catch (NumberFormatException e) {
            return -1;
        } catch (NoSuchElementException ne) {
            return 0;
        }
    }

    public int getNumberOfAdvisories() {
        try {
            if (isElementDisplayed(numberOfAdvisories)) {
                String advisoriesText = numberOfAdvisories.getText();
                String strNumber =
                        advisoriesText.substring(0, advisoriesText.toLowerCase().indexOf("advisor"))
                                .trim();
                System.out.println("Num adv: " + strNumber);
                return Integer.parseInt(strNumber);
            } else {
                return 0;
            }
        } catch (NumberFormatException e) {
            return -1;
        } catch (NoSuchElementException ne) {
            return 0;
        }
    }

    public MotTestPage expandAndShowFailures() {
        if (!failureResults.isDisplayed()) {
            numberOfFailures.click();
        }
        waitForElementToBeVisible(failureResults, 1);
        return this;
    }

    public MotTestPage collapseAndHiddenFailures() {
        if (failureResults.isDisplayed()) {
            numberOfFailures.click();
        }
        return this;
    }

    public MotTestPage expandAndShowPRS() {
        if (!prsResults.isDisplayed()) {
            numberOfPRS.click();
        }
        return this;
    }

    public MotTestPage collapseAndHiddenPRS() {
        if (prsResults.isDisplayed()) {
            numberOfPRS.click();
        }
        return this;
    }

    public MotTestPage expandAndShowAdvisories() {
        if (!advisoryResults.isDisplayed()) {
            numberOfAdvisories.click();
        }
        return this;
    }


    public BrakeTestConfigurationPage addBrakeTest() {
        addBrakeTest.click();
        return new BrakeTestConfigurationPage(driver);
    }

    public TestSummary createCertificate() {

        createCertificate.click();
        return new TestSummary(driver);
    }

    public WebElement getCreateCertificate() {
        return createCertificate;
    }


    /**
     * Luke Evans
     * Gets the status of the Review button (true = enabled, false = disabled)
     * The createCertificate.IsEnabled() WebDriver method returns true even if the object is disabled so could not use it
     */
    public Boolean isReviewButtonEnabled() {

        //Get disabled status of Review button from MOTTestPage
        String reviewButtonDisabled = createCertificate.getAttribute("disabled");

        if (!Boolean.valueOf(reviewButtonDisabled)) { //Cast to Boolean
            return true; //Review button is enabled
        } else { //Review button is disabled
            return false;
        }
    }

    public Boolean isAddBrakeTestButtonEnabled() {
        //Get disabled status of Review button from MOTTestPage
        String reviewButtonDisabled = addBrakeTest.getAttribute("disabled");

        if (!Boolean.valueOf(reviewButtonDisabled)) {
            return true; //button is enabled
        } else { //button is disabled
            return false;
        }
    }

    public MotTestPage addNewBrakeTest(Map<BrakeTestConfigurationPageField, Object> configEntry,
            Map<BrakeTestResultsPageField, Object> brakeTestEntry) {
        if (configEntry != null && brakeTestEntry != null)
            return addBrakeTest().enterBrakeConfigurationPageFields(configEntry).submit()
                    .enterBrakeResultsPageFields(brakeTestEntry).submit().clickDoneButton();
        else
            return this;
    }

    public MotTestPage addNewBrakeTest(Map<BrakeTestConfigurationPageField, Object> configEntry,
            Map<BrakeTestResultsPageField, Object> brakeTestEntry, String title) {
        if (configEntry != null && brakeTestEntry != null)
            return addBrakeTest().enterBrakeConfigurationPageFields(configEntry).submit()
                    .enterBrakeResultsPageFields(brakeTestEntry).submit().clickDoneButton(title);
        else
            return this;
    }

    public MotTestPage addManualAdvisory(ManualAdvisory manualAdvisory) {
        ReasonForRejectionPage reasonForRejectionPage =
                addRFR().addManualyAdvisor().submitManualAdvisory(manualAdvisory);
        reasonForRejectionPage.clickDone();
        return new MotTestPage(driver);
    }

    public MotTestPage addManualAdvisories(ManualAdvisory[] manualAdvisories) {
        for (ManualAdvisory manualAdvisory : manualAdvisories) {
            addManualAdvisory(manualAdvisory);
        }
        return new MotTestPage(driver);
    }

    public MotTestPage addFailure(FailureRejection failure) {
        return addRFR().addFailure(failure).clickDone();
    }

    public MotTestPage addFailure(FailureRejection failure, String title) {
        return addRFR().addFailure(failure).clickDone(title);
    }

    public MotTestPage addFailures(FailureRejection[] failures) {
        for (FailureRejection failureRejection : failures) {
            addFailure(failureRejection);
        }
        return new MotTestPage(driver);
    }

    public MotTestPage addFailures(FailureRejection[] failures, String title) {
        for (FailureRejection failureRejection : failures) {
            addFailure(failureRejection, title);
        }
        return new MotTestPage(driver, title);
    }

    public MotTestPage addPRS(PRSrejection prs) {
        return addRFR().addPRS(prs).clickDone();
    }

    public MotTestPage addPRS(PRSrejection[] prs) {
        for (PRSrejection prSrejection : prs) {
            addPRS(prSrejection);
        }
        return new MotTestPage(driver);
    }

    public MotTestPage addAdvisory(AdvisoryRejection advisory) {
        return addRFR().addAdvisory(advisory).clickDone();
    }

    public MotTestPage addAdvisories(AdvisoryRejection[] advisories) {
        for (AdvisoryRejection advisoryRejection : advisories) {
            addAdvisory(advisoryRejection);
        }
        return new MotTestPage(driver);
    }

    public MotTestPage removeRfR() {
        waitForElementToBeVisible(removeRfR,
                1); // Button wasn't immediately visible/clickable after section expanded
        removeRfR.click();
        waitForPageToLoad(); // Wait for the remove to complete before proceeding
        return this;
    }

    public MotTestPage addMotTest(String odometerValue,
            Map<BrakeTestConfigurationPageField, Object> configEntries,
            Map<BrakeTestResultsPageField, Object> brakeTestEntries, FailureRejection[] failures,
            PRSrejection[] prs, AdvisoryRejection[] advisories, ManualAdvisory[] manualAdvisories) {

        //Odometer
        enterOdometerValuesAndSubmit(odometerValue);// Send us parameter
        //Brake tests
        if (configEntries != null && brakeTestEntries != null)
            addNewBrakeTest(configEntries, brakeTestEntries);
        //Failures
        if (failures != null && failures.length > 0)
            addFailures(failures);
        //PRS
        if (prs != null && prs.length > 0)
            addPRS(prs);
        //Advisories
        if (advisories != null && advisories.length > 0)
            addAdvisories(advisories);
        //Manual Advisories added
        if (manualAdvisories != null && manualAdvisories.length > 0)
            addManualAdvisories(manualAdvisories);

        return new MotTestPage(driver);
    }

    public MotTestPage addMotTest(String odometerValue,
            Map<BrakeTestConfigurationPageField, Object> configEntries,
            Map<BrakeTestResultsPageField, Object> brakeTestEntries, FailureRejection[] failures,
            PRSrejection[] prs, AdvisoryRejection[] advisories, ManualAdvisory[] manualAdvisories,
            String title) {

        //Odometer
        enterOdometerValuesAndSubmit(odometerValue, title);// Send us parameter
        //Brake tests
        if (configEntries != null && brakeTestEntries != null)
            addNewBrakeTest(configEntries, brakeTestEntries, title);
        //Failures
        if (failures != null && failures.length > 0)
            addFailures(failures, title);
        //PRS
        if (prs != null && prs.length > 0)
            addPRS(prs);
        //Advisories
        if (advisories != null && advisories.length > 0)
            addAdvisories(advisories);
        //Manual Advisories added
        if (manualAdvisories != null && manualAdvisories.length > 0)
            addManualAdvisories(manualAdvisories);

        return new MotTestPage(driver, title);
    }

    public String getOdometerReadingNotice() {
        return odometerReadingNotice.getText();
    }

    public String getBrakeTestResultsNotice() {
        return brakeTestResultsNotice.getText();
    }

    public boolean isReviewTestButtonDisplayed() {

        return isElementDisplayed(createCertificate);
    }

    public void clearOdometerValue() {
        odometerField.clear();
    }

    public MotTestPage reSubmitOdometerValueForMotTest(String odometerValue) {
        clickUpdateOdometer();
        clearOdometerValue();
        typeOdometer(odometerValue);
        submitOdometer();
        return new MotTestPage(driver);
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
