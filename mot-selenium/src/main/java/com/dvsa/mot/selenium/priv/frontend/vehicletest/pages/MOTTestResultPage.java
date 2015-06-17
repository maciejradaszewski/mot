package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

import java.util.List;

public class MOTTestResultPage extends BasePage {

    private static String MOT_TEST_RESULT_PAGE = "MOT TEST RESULT";
    private static String MOT_RETEST_RESULT_PAGE = "MOT RE-TEST RESULT";

    @FindBy(id = "testStatus") private WebElement testStatus;

    @FindBy(id = "motTestNumber") private WebElement motTestNumber;

    @FindBy(id = "odometerReading") private WebElement odometerReading;

    @FindBy(id = "reprint-certificate") private WebElement reprintCertificate;

    @FindBy(id = "reprint-certificate-finish") private WebElement finishButton;

    @FindBy(id = "registrationNumber") private WebElement registrationNumber;

    @FindBy(id = "vinChassisNumber") private WebElement vinNumber;

    @FindBy(id = "make") private WebElement make;

    @FindBy(id = "model") private WebElement model;

    @FindBy(id = "colour") private WebElement colour;

    @FindBy(id = "fails") private WebElement fails;

    @FindBy(id = "prses") private WebElement prses;

    @FindBy(id = "logout") private WebElement logoutButton;

    public MOTTestResultPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        // TODO provisional try-catch. Should refactor
        try {
            checkTitle(MOT_TEST_RESULT_PAGE);
        } catch (IllegalStateException e) {
            checkTitle(MOT_RETEST_RESULT_PAGE);
        }
    }

    public String getTestStatus() {

        return testStatus.getText();
    }

    public boolean motTestStatus() {

        if (getTestStatus().equalsIgnoreCase("PASS")) {
            return true;
        } else if (getTestStatus().equalsIgnoreCase("Fail")) {
            return true;
        }
        return false;
    }

    public MOTTestResultPage getMotTestNumber() {
        motTestNumber.sendKeys();
        return new MOTTestResultPage(driver);
    }

    public String getOdometerReading() {
        return odometerReading.getText();
    }

    public String getRegistrationNumber() {
        return registrationNumber.getText();
    }

    public String getVinNumber() {
        return vinNumber.getText();
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

    public String getPrs() {
        return prses.findElement(By.id("rfrNoneRecorded")).getText();
    }

    public DuplicateReplacementCertificatePrintPage clickReprintCertificate() {
        reprintCertificate.click();
        return new DuplicateReplacementCertificatePrintPage(driver);
    }

    public UserDashboardPage clickFinishButton() {
        finishButton.click();
        return new UserDashboardPage(driver);
    }

    public boolean failuresContain(String reasonDescription) {
        List<WebElement> elements = fails.findElements(By.tagName("li"));

        for (WebElement element : elements) {
            if (element.getText().contains(reasonDescription)) {
                return true;
            }
        }

        return false;
    }

    public boolean isReprintCertificateButtonDisplayed() {

        return isElementDisplayed(reprintCertificate);
    }
}
