package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfigurationPageField;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResultsPageField;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

import java.util.Map;

public class MOTTestResultPageTestCompletePage extends BasePage {

    private static String PAGE_TITLE = "MOT TEST COMPLETE";

    @FindBy(id = "quit") private WebElement doneButton;

    @FindBy(id = "reprint-certificate") private WebElement reprintReceiptButton;

    @FindBy(id = "compareTestResults") private WebElement compareTestResults;

    @FindBy(id = "pass-certificate-item") private WebElement passCertificateItem;

    @FindBy(id = "refusal-certificate-item") private WebElement refusalCertificateItem;

    public MOTTestResultPageTestCompletePage(WebDriver driver) {
        this(driver, PAGE_TITLE);
    }

    public MOTTestResultPageTestCompletePage(WebDriver driver, String expectedTitle) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(expectedTitle);
    }

    public static MOTTestResultPageTestCompletePage navigateHereFromLoginPage(WebDriver driver,
            Login login, Vehicle vehicle, String odometerValue,
            Map<BrakeTestConfigurationPageField, Object> configEntries,
            Map<BrakeTestResultsPageField, Object> brakeTestEntries, FailureRejection[] failures,
            PRSrejection[] prs, AdvisoryRejection[] advisories, ManualAdvisory[] manualAdvisories,
            String passcode) {
        return TestSummary
                .navigateHereFromLoginPage(driver, login, vehicle, odometerValue, configEntries,
                        brakeTestEntries, failures, prs, advisories, manualAdvisories)
                .enterNewPasscode(passcode).clickFinishPrint();
    }

    public UserDashboardPage clickDoneButton() {
        doneButton.click();
        return new UserDashboardPage(driver);
    }

    public boolean passCertificateMessageIsPresent() {
        return isElementPresent(By.id("pass-certificate-item"));
    }

    public boolean refusalCertificateMessageIsPresent() {
        try {
            return refusalCertificateItem.isDisplayed();
        } catch (Exception e) {
            return false;
        }
    }
}
