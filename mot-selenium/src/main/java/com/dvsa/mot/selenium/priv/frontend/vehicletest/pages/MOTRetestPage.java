package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.AdvisoryRejection;
import com.dvsa.mot.selenium.datasource.FailureRejection;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.PRSrejection;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

public class MOTRetestPage extends MotTestPage {

    public static final String MOT_RETEST_PAGE_TITLE = "MOT RE-TEST RESULTS ENTRY";

    @FindBy(id = "viewBrakeTestResults") private WebElement viewResultsFail;

    @FindBy(id = "addBrakeTestResults") private WebElement viewResultsPass;

    @FindBy(id = "addBrakeTestResults") private WebElement addBrakeTestResults;

    public MOTRetestPage(WebDriver driver) {

        super(driver, MOT_RETEST_PAGE_TITLE);
    }

    public static MOTRetestPage navigateHereFromLoginPage(WebDriver driver, Login login,
            String previousIdNumber) {
        return VehicleConfirmationRetestPage
                .navigateHereFromLoginPage_PreviousNo(driver, login, previousIdNumber).startTest();
    }

    public MOTRetestPage addFailure(FailureRejection failure) {
        return addRFR().addFailure(failure).clickDoneExpectingMotRetestPage();
    }

    public MOTRetestPage addPRS(PRSrejection prs) {
        return addRFR().addPRS(prs).clickDoneExpectingMotRetestPage();
    }

    public MOTRetestPage addAdvisory(AdvisoryRejection advisory) {
        return addRFR().addAdvisory(advisory).clickDoneExpectingMotRetestPage();
    }

    public MOTRetestPage submitOdometer() {
        odometerSubmit.click();
        return new MOTRetestPage(driver);
    }

    public MOTRetestPage enterOdometerValuesAndSubmit(String odometerValue) {
        enterOdometerValues(odometerValue);
        submitOdometer();
        return new MOTRetestPage(driver);
    }

    public TestSummary createCertificate() {

        new WebDriverWait(driver, 1).until(ExpectedConditions
                .elementToBeClickable(findWebElement(By.id("createCertificate")))).click();

        return new RetestSummary(driver);
    }


}
