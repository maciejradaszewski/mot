package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class NewVehicleRecordSummaryPage extends BasePage {

    public static final String PAGE_TITLE = "SUMMARY OF NEW VEHICLE RECORD";

    @FindBy(id = "registrationNumber") private WebElement registrationNumber;

    @FindBy(id = "VIN") private WebElement vin;

    @FindBy(id = "emptyVrmReason") private WebElement emptyVrmReason;

    @FindBy(id = "emptyVinReason") private WebElement emptyVinReason;

    @FindBy(id = "make") private WebElement make;

    @FindBy(id = "countryOfRegistration") private WebElement countryOfRegistration;

    @FindBy(id = "transmissionType") private WebElement transmissionType;

    @FindBy(id = "dateOfFirstUse") private WebElement dateOfFirstUse;

    @FindBy(id = "vehicle-identification-change-this") private WebElement
            vehicleIdentificationChangeThis;

    @FindBy(id = "model") private WebElement model;

    @FindBy(id = "fuelType") private WebElement fuelType;

    @FindBy(id = "vehicleClass") private WebElement vehicleClass;

    @FindBy(id = "cylinderCapacity") private WebElement cylinderCapacity;

    @FindBy(id = "vehicle-specification-change-this") private WebElement
            vehicleSpecificationChangeDetails;

    @FindBy(id = "colour") private WebElement primaryColour;

    @FindBy(id = "secondaryColour") private WebElement secondaryColour;

    @FindBy(id = "confirm_test_result") private WebElement confirmAndSave;

    @FindBy(id = "oneTimePassword") private WebElement oneTimePassword;

    @FindBy(id = "otpErrorMessage") private WebElement otpErrorMessage;

    @FindBy(id = "otpErrorMessageDescription") private WebElement otpErrorMessageDescription;

    @FindBy(id = "back-link") private WebElement backLink;


    public NewVehicleRecordSummaryPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public static NewVehicleRecordSummaryPage navigateHereFromLoginPage(WebDriver driver,
            Login login, Vehicle vehicle) {
        return CreateNewVehicleRecordVehicleSpecificationPage
                .navigateHereFromLoginPage(driver, login, vehicle)
                .enterVehicleDetailsAndSubmit(vehicle);
    }

    public String getRegistrationNumber() {
        return registrationNumber.getText();
    }

    public String getVin() {
        return vin.getText();
    }

    public String getEmptyVrmReason() {
        return emptyVrmReason.getText();
    }

    public String getEmptyVinReason() {
        return emptyVinReason.getText();
    }

    public String getMake() {
        return make.getText();
    }

    public String getCountryOfRegistration() {
        return countryOfRegistration.getText();
    }

    public String getTransmissionType() {
        return transmissionType.getText();
    }

    public String getDateOfFirstUse() {
        return dateOfFirstUse.getText();
    }

    public CreateNewVehicleRecordVehicleIdentificationPage changeVehicleIdentificationDetails() {
        vehicleIdentificationChangeThis.click();
        return new CreateNewVehicleRecordVehicleIdentificationPage(driver);
    }

    public String getModel() {
        return model.getText();
    }

    public String getFuelType() {
        return fuelType.getText();
    }

    public String getVehicleClass() {
        return vehicleClass.getText();
    }

    public String getCylinderCapacity() {
        return cylinderCapacity.getText();
    }

    public CreateNewVehicleRecordVehicleSpecificationPage changeVehicleSpecificationDetails() {
        vehicleSpecificationChangeDetails.click();
        return new CreateNewVehicleRecordVehicleSpecificationPage(driver);
    }

    public String getColour() {
        return primaryColour.getText();
    }

    public String getSecondaryColour() {
        return secondaryColour.getText();
    }

    public NewVehicleRecordCompletionPage confirmAndSave(String oneTimePassword) {
        enterOneTimePassword(oneTimePassword);
        confirmAndSave.click();
        return new NewVehicleRecordCompletionPage(driver);
    }

    public NewVehicleRecordSummaryPage confirmAndSaveExpectingError(String oneTimePassword) {
        enterOneTimePassword(oneTimePassword);
        confirmAndSave.click();
        return new NewVehicleRecordSummaryPage(driver);
    }

    public NewVehicleRecordSummaryPage enterOneTimePassword(String otp) {
        oneTimePassword.sendKeys(otp);
        return this;
    }

    public CreateNewVehicleRecordVehicleSpecificationPage clickOnBackLink() {
        backLink.click();
        return new CreateNewVehicleRecordVehicleSpecificationPage(driver);
    }
    public boolean isErrorMessageDisplayed(){
        return  ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
