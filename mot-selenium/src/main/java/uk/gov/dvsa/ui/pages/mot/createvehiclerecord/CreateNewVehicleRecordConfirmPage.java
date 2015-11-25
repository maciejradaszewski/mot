package uk.gov.dvsa.ui.pages.mot.createvehiclerecord;


import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class CreateNewVehicleRecordConfirmPage extends Page {

    public static final String PAGE_TITLE = "Confirm new vehicle record";
    public static final String PATH = "/vehicle-step/confirm";

    @FindBy(id = "registrationNumber") private WebElement registrationNumber;
    @FindBy(id = "VIN") private WebElement vin;
    @FindBy(id = "emptyVrmReason") private WebElement emptyVrmReason;
    @FindBy(id = "emptyVinReason") private WebElement emptyVinReason;
    @FindBy(id = "make") private WebElement make;
    @FindBy(id = "countryOfRegistration") private WebElement countryOfRegistration;
    @FindBy(id = "transmissionType") private WebElement transmissionType;
    @FindBy(id = "dateOfFirstUse") private WebElement dateOfFirstUse;
    @FindBy(id = "vehicle-identification-change-this") private WebElement vehicleIdentificationChangeThis;
    @FindBy(id = "model") private WebElement model;
    @FindBy(id = "fuelType") private WebElement fuelType;
    @FindBy(id = "vehicleClass") private WebElement vehicleClass;
    @FindBy(id = "cylinderCapacity") private WebElement cylinderCapacity;
    @FindBy(id = "vehicle-specification-change-this") private WebElement vehicleSpecificationChangeDetails;
    @FindBy(id = "colour") private WebElement primaryColour;
    @FindBy(id = "secondaryColour") private WebElement secondaryColour;
    @FindBy(id = "confirm_test_result") private WebElement startMOTTest;
    @FindBy(id = "oneTimePassword") private WebElement oneTimePassword;
    @FindBy(id = "otpErrorMessage") private WebElement otpErrorMessage;
    @FindBy(id = "otpErrorMessageDescription") private WebElement otpErrorMessageDescription;
    @FindBy(id = "back-link") private WebElement backLink;
    @FindBy(id = "declarationStatement") private WebElement declarationElement;


    public CreateNewVehicleRecordConfirmPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }


    public String getDeclarationStatement() { return declarationElement.getText(); }

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

    public String getColour() {
        return primaryColour.getText();
    }

    public String getSecondaryColour() {
        return secondaryColour.getText();
    }

    public boolean isDeclarationTextDisplayed() {
        return declarationElement.isDisplayed();
    }

    public String getDeclarationText() {
        return declarationElement.getText();
    }
}