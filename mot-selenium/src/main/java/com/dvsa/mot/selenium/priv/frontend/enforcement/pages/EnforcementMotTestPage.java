package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.ReasonToCancel;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest.pages.BrakeTestConfigurationPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.*;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class EnforcementMotTestPage extends BasePage {

    private static final String DEFAULT_PAGE_TITLE = "MOT REINSPECTION RESULTS ENTRY";

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

    @FindBy(id = "odometer_submit") protected WebElement odometerSubmit;

    @FindBy(id = "odometerReading") private WebElement odometerReading;

    @FindBy(id = "addBrakeTestResults") private WebElement addBrakeTest;

    @FindBy(id = "brakeTestResult") private WebElement brakeTestResult;

    @FindBy(id = "viewBrakeTestResults") private WebElement viewBrakeTestResults;

    @FindBy(id = "createCertificate") protected WebElement createCertificate;

    @FindBy(id = "odometerReadingNotice") private WebElement odometerReadingNotice;

    @FindBy(id = "brakeTestResultsNotice") private WebElement brakeTestResultsNotice;

    @FindBy(id = "reason-for-rejection-modal") private WebElement viewReasonsForRejectionsLink;

    @FindBy(id = "quit") private WebElement motDone;

    @FindBy(id = "rfr-modal-close") private WebElement closeRFRList;

    @FindBy(id = "rfr-remove") private WebElement removeRfR;

    @FindBy(id = "vehicle-summary-more") private WebElement vehicleSummaryMore;

    @FindBy(linkText = "Edit") private WebElement editButton;

    @FindBy(id = "vtsNameAndAddress") private WebElement inspectionLocation;

    public EnforcementMotTestPage(WebDriver driver) {
        this(driver, DEFAULT_PAGE_TITLE);
    }

    public EnforcementMotTestPage(WebDriver driver, String title) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(title);
    }

    public static MotTestPage navigateHereFromLoginPage(WebDriver driver, Login login,
            Vehicle vehicle) {
        return MotTestOptionsPage.navigateHereFromLoginPage(driver, login, vehicle).returnToHome()
                .resumeMotTest();
    }

    public ReasonsToCancelPage clickCancelMotTest() {
        cancelMotTest.click();
        return new ReasonsToCancelPage(driver);
    }

    public UserDashboardPage cancelMotTest(ReasonToCancel reason) {
        return clickCancelMotTest().enterAndSubmitReasonsToCancelPageExpectingAbortedPage(reason)
                .clickFinish();
    }

    public EnforcementMotTestPage enterOdometerValues(String odometerValue) {
        clickUpdateOdometer();
        typeOdometer(odometerValue);
        return this;
    }

    public EnforcementMotTestPage enterOdometerValuesAndSubmit(String odometerValue, String title) {
        enterOdometerValues(odometerValue);
        submitOdometer(title);
        return new EnforcementMotTestPage(driver, title);
    }

    public void typeOdometer(String text) {
        odometerField.sendKeys(text);
    }

    public EnforcementMotTestPage clickUpdateOdometer() {
        odometerUpdateButton.click();
        waitForElementToBeVisible(odometerSubmit, 1);
        return this;
    }

    public EnforcementMotTestPage submitOdometer(String title) {
        odometerSubmit.click();
        return new EnforcementMotTestPage(driver, title);
    }

    public BrakeTestConfigurationPage addBrakeTest() {
        addBrakeTest.click();
        return new BrakeTestConfigurationPage(driver);
    }
}
