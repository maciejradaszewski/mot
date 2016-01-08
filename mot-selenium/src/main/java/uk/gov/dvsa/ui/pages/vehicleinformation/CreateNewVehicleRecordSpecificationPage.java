package uk.gov.dvsa.ui.pages.vehicleinformation;

import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class CreateNewVehicleRecordSpecificationPage extends Page {

        private static final String PAGE_TITLE = "Create a new vehicle record";
        public static final String PATH = "/vehicle-step/add-step-two";

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

    public CreateNewVehicleRecordSpecificationPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public void enterVehicleModel(Vehicle vehicle) {
        Select modelSelect = new Select(model);
        modelSelect.selectByValue(vehicle.getModel());
    }

    public void enterVehicleFuelType(Vehicle vehicle) {
        Select fuelTypeSelect = new Select(fuelType);
        fuelTypeSelect.selectByValue(vehicle.getFuelType());
    }

    public void enterVehicleClass(Vehicle vehicle) {
        Select vehicleClassSelect = new Select(this.vehicleClass);
        vehicleClassSelect.selectByValue(vehicle.getTestClass());
    }

    public void enterCylinderCapacity(Vehicle vehicle) {
        cylinderCapacity.sendKeys(vehicle.getCylinderCapacity());
    }

    public void enterColour(Vehicle vehicle) {
        Select colourSelect = new Select(this.colour);
        colourSelect.selectByValue(vehicle.getColour());
    }

    public void enterSecondaryColour(Vehicle vehicle) {
        Select colourSelect = new Select(this.secondaryColour);
        colourSelect.selectByValue(vehicle.getSecondaryColour());
    }

    public void enterVehicleDetails(Vehicle vehicle) {
        enterVehicleModel(vehicle);
        enterVehicleFuelType(vehicle);
        enterVehicleClass(vehicle);
        enterCylinderCapacity(vehicle);
        enterColour(vehicle);
        enterSecondaryColour(vehicle);
    }

    public CreateNewVehicleRecordConfirmPage submit() {
        submitButton.click();
        return new CreateNewVehicleRecordConfirmPage(driver);
    }
}