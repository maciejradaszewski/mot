package uk.gov.dvsa.ui.pages.mot.createvehiclerecord;

import com.dvsa.mot.selenium.framework.util.RadioList;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class CreateNewVehicleRecordIdentificationPage extends Page {

    public static final String PAGE_TITLE = "Create a new vehicle record";
    public static final String PATH = "/vehicle-step/add-step-one";

    @FindBy(tagName = "legend") private WebElement step;
    @FindBy(id = "registrationNumber") private WebElement registrationNumber;
    @FindBy(id = "VIN") private WebElement vin;
    @FindBy(id = "make") private WebElement make;
    @FindBy(id = "day") private WebElement day;
    @FindBy(id = "month") private WebElement month;
    @FindBy(id = "year") private WebElement year;
    @FindBy(id = "countryOfRegistration") private WebElement registrationCountry;
    @FindBy(id = "submit-button") private WebElement submit;
    @FindBy(id = "cancel-link") private WebElement cancel;
    @FindBy(id = "other-make-v") private WebElement otherMake;
    @FindBy(id = "emptyVrmReason") private WebElement emptyVrmReason;
    @FindBy(id = "emptyVinReason") private WebElement emptyVinReason;
    @FindBy(name = "vehicleForm[transmissionType]") private WebElement transmissionType;

    public CreateNewVehicleRecordIdentificationPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public void selectCountryOfRegistration(Vehicle vehicle) {
        Select selectCountryOfRegistration = new Select(registrationCountry);
        selectCountryOfRegistration.selectByIndex(1);
    }

    public void setRegistrationNumber(Vehicle vehicle) {
        registrationNumber.sendKeys(vehicle.getRegistrationNumber());
    }

    public void setVin(Vehicle vehicle) {
        this.vin.sendKeys(vehicle.getVin());
    }

    public void selectMakeOfVehicle(Vehicle vehicle) {
        Select selectMake = new Select(make);
        selectMake.selectByValue(vehicle.getMake());
    }

    public void setDate(Vehicle vehicle) {
        String dateSplit[] = vehicle.getDateOfFirstUse().split("-");
        this.year.sendKeys(dateSplit[0]);
        this.month.sendKeys(dateSplit[1]);
        this.day.sendKeys(dateSplit[2]);
    }

    public void selectTransmissionType(Vehicle vehicle) {
        RadioList radioList = new RadioList(driver.findElements(By.name("vehicleForm[transmissionType]")));
        if (vehicle.getTransmissionType() == "a") {
            radioList.findByValue("1").click();
        }
        else if (vehicle.getTransmissionType() == "m") {
            radioList.findByValue("2").click();
        }
    }

    public void enterDetails(Vehicle vehicle) {
        selectCountryOfRegistration(vehicle);
        registrationNumber.clear();
        setRegistrationNumber(vehicle);
        vin.clear();
        setVin(vehicle);
        setDate(vehicle);
        selectMakeOfVehicle(vehicle);
        selectTransmissionType(vehicle);
    }

    public CreateNewVehicleRecordSpecificationPage submit() {
        submit.click();
        return new CreateNewVehicleRecordSpecificationPage(driver);
    }
}