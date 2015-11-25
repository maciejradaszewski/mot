package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.enums.Colour;
import com.dvsa.mot.selenium.datasource.enums.FuelTypes;
import com.dvsa.mot.selenium.datasource.enums.VehicleClasses;
import com.dvsa.mot.selenium.datasource.enums.VehicleModel;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;

public class CreateNewVehicleRecordVehicleSpecificationPage extends BasePage {
    private static final String PAGE_TITLE = "CREATE A NEW VEHICLE RECORD";
    private static final String STEP_TITLE = "Vehicle specification";

    @FindBy(tagName = "legend") private WebElement step;

    @FindBy(id = "model") private WebElement model;

    @FindBy(id = "fuelType") private WebElement fuelType;

    @FindBy(id = "vehicleClass") private WebElement vehicleClass;

    @FindBy(id = "cylinderCapacity") private WebElement cylinderCapacity;

    @FindBy(id = "colour") private WebElement colour;

    @FindBy(id = "secondaryColour") private WebElement secondaryColour;

    @FindBy(id = "submit-button") private WebElement submitButton;

    @FindBy(id = "back-link") private WebElement backLink;

    @FindBy(id = "modelOther") private WebElement modelOther;

    public CreateNewVehicleRecordVehicleSpecificationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
        if (checkPageTitles && !step.getText().equalsIgnoreCase(STEP_TITLE)) {
            throw new IllegalStateException(
                    "This is not the " + STEP_TITLE + ". Actual step [" + step.getText() + "]");
        }
    }

    public static CreateNewVehicleRecordVehicleSpecificationPage navigateHereFromLoginPage(
            WebDriver driver, Login login, Vehicle vehicle) {
        return CreateNewVehicleRecordVehicleIdentificationPage
                .navigateHereFromLoginPage(driver, login, vehicle)
                .enterVehicleDetailsAndSubmit(vehicle);
    }

    public CreateNewVehicleRecordVehicleSpecificationPage enterVehicleModel(
            VehicleModel vehicleModel) {
        Select modelSelect = new Select(model);
        modelSelect.selectByValue(vehicleModel.getModelId());
        return this;
    }

    public CreateNewVehicleRecordVehicleSpecificationPage enterVehicleFuelType(
            FuelTypes vehicleFuelType) {
        Select fuelTypeSelect = new Select(fuelType);
        fuelTypeSelect.selectByValue(vehicleFuelType.getFuelId());
        return this;
    }

    public CreateNewVehicleRecordVehicleSpecificationPage enterVehicleClass(
            VehicleClasses vehicleClass) {
        Select vehicleClassSelect = new Select(this.vehicleClass);
        vehicleClassSelect.selectByValue(vehicleClass.getId());
        return this;
    }

    public CreateNewVehicleRecordVehicleSpecificationPage enterCylinderCapacity(String capacity) {
        cylinderCapacity.sendKeys(capacity);
        return this;
    }

    public CreateNewVehicleRecordVehicleSpecificationPage enterVehicleDetails(Vehicle vehicle) {
        enterVehicleModel(vehicle.model);
        enterVehicleFuelType(vehicle.fuelType);
        enterVehicleClass(vehicle.vehicleClass);
        enterCylinderCapacity(Integer.toString(vehicle.cylinderCapacity));
        enterColour(vehicle.primaryColour);
        enterSecondaryColour(vehicle.secondaryColour);
        return this;
    }

    public CreateNewVehicleRecordVehicleSpecificationPage enterVehicleDetailsWithOutCylinderCapacity(
            Vehicle vehicle) {
        enterVehicleModel(vehicle.model);
        enterVehicleFuelType(vehicle.fuelType);
        enterVehicleClass(vehicle.vehicleClass);
        enterColour(vehicle.primaryColour);
        enterSecondaryColour(vehicle.secondaryColour);
        return this;
    }

    public CreateNewVehicleRecordVehicleSpecificationPage enterVehicleDetailsWithOtherModel(
            Vehicle vehicle) {
        enterVehicleModel(vehicle.model.Other);
        enterVehicleFuelType(vehicle.fuelType);
        enterVehicleClass(vehicle.vehicleClass);
        enterCylinderCapacity(Integer.toString(vehicle.cylinderCapacity));
        enterColour(vehicle.primaryColour);
        enterSecondaryColour(vehicle.secondaryColour);
        return this;
    }

    public CreateNewVehicleRecordVehicleSpecificationPage enterOtherModel() {
        modelOther.sendKeys("NewVehicleModel07TestDisplayed");
        return this;
    }


    public boolean isCorrectDetailsDisplayed(Vehicle vehicle) {
        return (model.getAttribute("value").equalsIgnoreCase(vehicle.model.getModelId())) &&
                fuelType.getAttribute("value").equalsIgnoreCase(vehicle.fuelType.getFuelId()) &&
                cylinderCapacity.getAttribute("value")
                        .equalsIgnoreCase(Integer.toString(vehicle.cylinderCapacity)) &&
                vehicleClass.getAttribute("value").equalsIgnoreCase(vehicle.vehicleClass.getId()) &&
                colour.getAttribute("value").equalsIgnoreCase(vehicle.primaryColour.getColourId())
                &&
                secondaryColour.getAttribute("value")
                        .equalsIgnoreCase(vehicle.secondaryColour.getColourId());
    }

    public CreateNewVehicleRecordVehicleSpecificationPage enterColour(Colour colour) {
        Select colourSelect = new Select(this.colour);
        colourSelect.selectByValue(colour.getColourId());
        return this;
    }

    public NewVehicleRecordConfirmPage submit() {
        submitButton.click();
        return new NewVehicleRecordConfirmPage(driver);
    }

    public CreateNewVehicleRecordVehicleSpecificationPage submitDetailsExpectingError() {
        submitButton.click();
        return this;
    }


    public NewVehicleRecordConfirmPage enterVehicleDetailsAndSubmit(Vehicle vehicle) {
        enterVehicleDetails(vehicle);
        return submit();
    }

    public CreateNewVehicleRecordVehicleSpecificationPage enterSecondaryColour(Colour colour) {
        Select colourSelect = new Select(this.secondaryColour);
        colourSelect.selectByValue(colour.getColourId());
        return this;
    }

    public CreateNewVehicleRecordVehicleIdentificationPage backLink() {
        backLink.click();
        return new CreateNewVehicleRecordVehicleIdentificationPage(driver);
    }

    public CreateNewVehicleRecordVehicleSpecificationPage enterCylinderCapacityValue(
            Vehicle vehicle) {
        cylinderCapacity.clear();
        enterCylinderCapacity(Integer.toString(vehicle.cylinderCapacity));
        return this;
    }

    public CreateNewVehicleRecordVehicleSpecificationPage enterWrongCylinderCapacityValue() {
        enterCylinderCapacity("50000");
        return this;
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }

}
