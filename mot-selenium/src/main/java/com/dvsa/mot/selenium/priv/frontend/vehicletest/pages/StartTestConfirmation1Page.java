package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.enums.FuelTypes;
import com.dvsa.mot.selenium.datasource.enums.VehicleClasses;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.CacheLookup;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;

public class StartTestConfirmation1Page extends BasePage {
    public String pageTitle;

    public static final String DEFAULT_PAGE_TITLE = "CONFIRM VEHICLE FOR TEST";

    @FindBy(id = "user-info") @CacheLookup private WebElement testerVTSInfo;

    @FindBy(className = "active") @CacheLookup private WebElement stepInfo;

    @FindBy(className = "clearfix") @CacheLookup private WebElement carInfoTable;

    @FindBy(id = "abort_vehicle_confirmation") @CacheLookup private WebElement retry;

    @FindBy(id = "vehicleMake") private WebElement carMakeAndModel;

    //TODO Refactor
    @FindBy(css = ".col-md-6:last-child>.dl-standard") @CacheLookup private WebElement carYear;

    @FindBy(id = "primary-colour") @CacheLookup private WebElement primaryColor;

    @FindBy(id = "secondary-colour") private WebElement secondaryColour;

    @FindBy(id = "refuse-to-test") private WebElement refuseTest;

    @FindBy(id = "confirm_vehicle_confirmation") private WebElement confirmButton;

    @FindBy(id = "abort_vehicle_confirmation") private WebElement cancelButton;

    @FindBy(partialLinkText = "Return to Vehicle search") private WebElement backToSearch;

    @FindBy(className = "message-exclamation-triangle") private WebElement rejectTitle;

    @FindBy(id = "unavailableForRetestMessage") private WebElement rejectMessage;

    @FindBy(id = "expiryInfoAlert") private WebElement expiryInfoAlert;

    @FindBy(id = "motExpiryDate") private WebElement motExpiryDate;

    @FindBy(id = "fuel-type-select") private WebElement fuelType;

    @FindBy(id = "vehicle-class-select") private WebElement vehicleClass;

    @FindBy(id = "vehicleTransmission") private WebElement vehicleTransmission;

    @FindBy(id = "vehicleRegistrationNumber") private WebElement vehicleRegistrationNumber;

    @FindBy(id = "vehicleVINnumber") private WebElement vehicleVINnumber;

    @FindBy(id = "vehicleCylinderCapacity") private WebElement vehicleCylinderCapacity;

    @FindBy(id = "vehicleDoors") private WebElement vehicleDoors;

    @FindBy(id = "vehicleFirstUse") private WebElement vehicleFirstUse;

    @FindBy(id = "inProgressTestExistsAlert") private WebElement inProgressTestExistsAlert;


    public static StartTestConfirmation1Page navigateHereFromLoginPageAsMotTest(WebDriver driver,
            Login login, Vehicle vehicle) {
        return VehicleSearchPage.navigateHereFromLoginPage(driver, login)
                .submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg);
    }

    public static StartTestConfirmation1Page navigateHereFromLoginAsManyVTSTester(WebDriver driver,
            Login login, Vehicle vehicle, Site site) {
        return VehicleSearchPage.navigateHereFromLoginPageForManyVtsTester(driver, login, site)
                .submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg);
    }

    public StartTestConfirmation1Page(WebDriver driver) {
        this(driver, DEFAULT_PAGE_TITLE);
    }

    public StartTestConfirmation1Page(WebDriver driver, String pageTitle) {
        super(driver);
        this.pageTitle = pageTitle;
        checkTitle(this.pageTitle);
    }

    public String getCarMakeAndModel() {
        return carMakeAndModel.getText();
    }

    public String getRegistration() {
        return vehicleRegistrationNumber.getText();
    }

    public String getVIN() {
        return vehicleVINnumber.getText();
    }

    public String getFuel() {
        Select s = new Select(fuelType);
        return s.getFirstSelectedOption().getText();
    }

    public StartTestConfirmation1Page selectVehicleFuel(FuelTypes vehicleFuelType) {
        Select s = new Select(this.fuelType);
        s.selectByValue(vehicleFuelType.getFuelId());
        return this;
    }

    public String getVehicleClass() {
        Select s = new Select(vehicleClass);
        return s.getFirstSelectedOption().getText();
    }

    public StartTestConfirmation1Page selectVehicleClass(VehicleClasses vehicleClass) {
        Select s = new Select(this.vehicleClass);
        s.selectByValue(vehicleClass.getId());
        return this;
    }

    public String getTransmission() {
        return vehicleTransmission.getText();
    }


    public String getPrimaryColor() {
        Select primaryColorDropdown = new Select(primaryColor);
        return primaryColorDropdown.getFirstSelectedOption().getText();
    }

    public String getSecondaryColor() {
        Select secondaryColorDropdown = new Select(secondaryColour);
        return secondaryColorDropdown.getFirstSelectedOption().getText();
    }

    public UserDashboardPage clickRetry() {
        retry.click();
        return new UserDashboardPage(driver);
    }

    public String getUserInfo() {
        return testerVTSInfo.getText();
    }

    public RefuseToTestPage clickRefuseVehicle() {
        refuseTest.click();
        return new RefuseToTestPage(driver);
    }

    public MotTestStartedPage submitConfirm() {
        confirmButton.click();

        if (exist2FAFieldInCurrentPage()) {
            VehicleDetailsChangedPage vehicleDetailsChangedPage =
                    new VehicleDetailsChangedPage(driver);
            return vehicleDetailsChangedPage.confirmVehicleChanges(Text.TEXT_PASSCODE);
        } else {
            return new MotTestStartedPage(driver);
        }
    }

    public MotTestPage confirmStartTest() {
        confirmButton.click();
        return new MotTestPage(driver);
    }

    public MotTestPage confirmStartReTest(String myPageTitle) {
        confirmButton.click();
        this.clickHome().resumeMotTestExpectingMOTRetestPage();
        return new MotTestPage(driver, myPageTitle);
    }

    public MotTestPage submitDemoConfirm() {
        confirmButton.click();
        return new MotTestPage(driver);
    }

    public MotTestPage startTest() {
        return submitConfirm().returnToHome().resumeMotTest();
    }

    public MotTestPage confirmDemoTest() {
        return submitDemoConfirm();
    }

    public VehicleDetailsChangedPage submitConfirmExpectingVehicleDetailsChangedPage() {
        confirmButton.click();
        return new VehicleDetailsChangedPage(driver);
    }


    public StartTestConfirmation1Page submitConfirmExpectingError() {
        confirmButton.click();
        return new StartTestConfirmation1Page(driver);
    }

    public String getRejectTitle() {
        return rejectTitle.getText();
    }

    public String getRejectMessage() {
        return rejectMessage.getText();

    }

    public String getExpiryInfoAlert() {
        return expiryInfoAlert.getText();
    }

    public boolean isPresentExpiryInfoAlert() {
        try {
            return expiryInfoAlert.isDisplayed();
        } catch (Exception e) {
            return false;
        }
    }

    public VehicleSearchPage clickSearchAgain() {
        backToSearch.click();
        return new VehicleSearchPage(driver);
    }

    public String getInProgressTestExistsAlert() {
        return inProgressTestExistsAlert.getText();
    }

    public UserDashboardPage clickCancel() {
        cancelButton.click();
        return new UserDashboardPage(driver);
    }

    public boolean isStartMotTestButtonDisplayed() {
        return isElementDisplayed(confirmButton);
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
