package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.Utilities;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;

public class ReplacementCertificateReviewPage extends BasePage {

    private static String REPLACEMENT_CERT_REVIEW_PAGE = "REPLACEMENT CERTIFICATE REVIEW";
    private static String REPLACEMENT_CERT_UPDATE_PAGE = "REPLACEMENT CERTIFICATE UPDATE";

    @FindBy(id = "cancelMotTest") private WebElement cancelAndReturnToVehicle;

    @FindBy(id = "testStatus") private WebElement testStatus;

    @FindBy(id = "expiryDate") private WebElement expiryDate;

    @FindBy(id = "registrationNumber") private WebElement registrationNumber;

    @FindBy(id = "vinChassisNumber") private WebElement vinNumber;

    @FindBy(id = "testClass") private WebElement vehicleClass;

    @FindBy(id = "make") private WebElement vehicleMake;

    @FindBy(id = "motTestNumber") private WebElement motTestNumber;

    @FindBy(id = "model") private WebElement vehicleModel;

    @FindBy(id = "colour") private WebElement vehicleColours;

    @FindBy(id = "brakeResults") private WebElement brakeResults;

    @FindBy(id = "prses") private WebElement prsRecorded;

    @FindBy(id = "advisoryText") private WebElement advisoriesRecorded;

    @FindBy(id = "odometerReading") private WebElement odometerReading;

    @FindBy(id = "confirm_and_print") private WebElement printReplacementCertificate;

    @FindBy(id = "oneTimePassword") private WebElement oneTimePassword;

    @FindBy(id = "otpErrorMessage") private WebElement otpErrorMessage;

    @FindBy(id = "otpErrorMessageDescription") private WebElement otpErrorMessageDescription;

    @FindBy(xpath = "//a[text()='Report a faulty card']") private WebElement reportFaultyCardLink;

    @FindBy(id = "vtsNameAndAddress") private WebElement vtsName;

    @FindBy(id = "reasonForDifferentTester") private WebElement reasonForDifferentTester;

    public ReplacementCertificateReviewPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        //TODO Provisional try-catch. Should be refactored
        try {
            checkTitle(REPLACEMENT_CERT_REVIEW_PAGE);
        } catch (IllegalStateException e) {
            checkTitle(REPLACEMENT_CERT_UPDATE_PAGE);
        }
    }

    public DuplicateReplacementCertificateSearchPage cancelAndReturnToVehicleButton() {
        cancelAndReturnToVehicle.click();
        return new DuplicateReplacementCertificateSearchPage(driver);
    }

    public String testStatus() {
        return testStatus.getText();
    }

    public String registrationNumber() {
        return registrationNumber.getText();
    }

    public String vinNumber() {
        return vinNumber.getText();
    }

    public String vehicleClass() {
        return vehicleClass.getText();
    }

    public String vehicleMake() {
        return vehicleMake.getText();
    }

    public String vehicleModel() {
        return vehicleModel.getText();
    }

    public String vehicleColours() {
        return vehicleColours.getText();
    }

    public String odometerReading() {
        return odometerReading.getText();
    }

    public String getVtsName() {
        return vtsName.getText();
    }

    public ReplacementCertificateReviewPage enterOneTimePassword(String password) {
        oneTimePassword.sendKeys(password);
        return this;
    }


    public ReplacementCertificateCompletePage finishAndPrintCertificate() {
        disablePrintingOnCurrentPage();
        printReplacementCertificate.click();
        return new ReplacementCertificateCompletePage(driver);
    }

    public ReplacementCertificateCompletePage finishAndPrintCertificate(String oneTimePassword) {
        enterOneTimePassword(oneTimePassword);
        return finishAndPrintCertificate();
    }

    public ReplacementCertificateReviewPage finishAndPrintCertificateExpectingError() {
        disablePrintingOnCurrentPage();
        printReplacementCertificate.click();
        return new ReplacementCertificateReviewPage(driver);
    }

    public ReplacementCertificateReviewPage selectReasonForDifferentTesterByIndex(int position) {
        Select s = new Select(reasonForDifferentTester);
        s.selectByIndex(position);
        return this;
    }
    public String getMotTestNumber() {
        return motTestNumber.getText();
    }

    public String generateNewVT30FileName() {
        return "VT30" + Utilities.getSystemDateAndTime();
    }
}
