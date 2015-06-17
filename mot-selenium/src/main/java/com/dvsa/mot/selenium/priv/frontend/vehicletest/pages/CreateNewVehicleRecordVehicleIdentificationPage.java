package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.enums.CountryOfRegistration;
import com.dvsa.mot.selenium.datasource.enums.EmptyRegAndVin;
import com.dvsa.mot.selenium.datasource.enums.VehicleMake;
import com.dvsa.mot.selenium.datasource.enums.VehicleTransmissionType;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.RadioList;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;

import java.util.List;

public class CreateNewVehicleRecordVehicleIdentificationPage extends BasePage {

    public static final String PAGE_TITLE = "CREATE A NEW VEHICLE RECORD";
    private static final String STEP_TITLE = "Vehicle identification";


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



    public CreateNewVehicleRecordVehicleIdentificationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
        if (checkPageTitles && !step.getText().equalsIgnoreCase(STEP_TITLE)) {
            throw new IllegalStateException(
                    "This is not the " + STEP_TITLE + ". Actual step [" + step.getText() + "]");
        }
    }

    public static CreateNewVehicleRecordVehicleIdentificationPage navigateHereFromLoginPage(
            WebDriver driver, Login login, Vehicle vehicle) {
        return VehicleSearchPage.navigateHereFromLoginPage(driver, login).typeReg(vehicle.carReg)
                .typeVIN(vehicle.fullVIN).submitSearchExpectingError().submitSearchExpectingError()
                .createNewVehicle();
    }

    public CreateNewVehicleRecordVehicleIdentificationPage setRegistrationNumber(String reg) {
        registrationNumber.sendKeys(reg);
        return this;
    }

    public CreateNewVehicleRecordVehicleIdentificationPage setVin(String vin) {
        this.vin.sendKeys(vin);
        return this;
    }

    public CreateNewVehicleRecordVehicleIdentificationPage setDay(String day) {
        day = (day != null && day.length() == 1) ? "0" + day : day;
        this.day.sendKeys(day);
        return this;
    }

    public CreateNewVehicleRecordVehicleIdentificationPage setMonth(String month) {
        month = (month != null && month.length() == 1) ? "0" + month : month;
        this.month.sendKeys(month);
        return this;
    }

    public CreateNewVehicleRecordVehicleIdentificationPage setYear(String year) {
        this.year.sendKeys(year);
        return this;
    }

    public CreateNewVehicleRecordVehicleIdentificationPage selectMakeOfVehicle(
            VehicleMake vehicleMake) {
        Select selectMake = new Select(make);
        selectMake.selectByValue(vehicleMake.getVehicleID());
        return this;
    }

    private RadioList getTransmissionTypeRadioList() {
        return new RadioList(driver.findElements(By.name("vehicleForm[transmissionType]")));
    }

    public CreateNewVehicleRecordVehicleIdentificationPage selectTransmissionType(
            VehicleTransmissionType transmissionType) {

        getTransmissionTypeRadioList().findByValue(transmissionType.getTransmissionId()).click();
        return this;
    }

    public CreateNewVehicleRecordVehicleSpecificationPage submit() {
        submit.click();
        return new CreateNewVehicleRecordVehicleSpecificationPage(driver);
    }


    public CreateNewVehicleRecordVehicleIdentificationPage selectCountryOfRegistration(
            CountryOfRegistration countryOfRegistration) {
        Select selectCountryOfRegistration = new Select(registrationCountry);
        List<WebElement> l = selectCountryOfRegistration.getOptions();
        selectCountryOfRegistration
                .selectByValue(countryOfRegistration.getcountryOfRegistrationID());
        return this;
    }

    public CreateNewVehicleRecordVehicleIdentificationPage enterVehicleDetails(Vehicle vehicle) {
        setDay(Integer.toString(vehicle.dateOfFirstUse.getDayOfMonth()));
        setMonth(Integer.toString(vehicle.dateOfFirstUse.getMonthOfYear()));
        setYear(Integer.toString(vehicle.dateOfFirstUse.getYear()));
        selectMakeOfVehicle(vehicle.make);
        selectCountryOfRegistration(vehicle.countryOfRegistration);
        selectTransmissionType(vehicle.transType);
        return this;
    }

    public CreateNewVehicleRecordVehicleIdentificationPage clearDateField() {
        day.clear();
        month.clear();
        year.clear();
        return this;
    }

    public CreateNewVehicleRecordVehicleIdentificationPage enterAllVehicleDetails(Vehicle vehicle) {
        setRegistrationNumber(vehicle.carReg);
        setVin(vehicle.fullVIN);
        selectReasonForEmptyRegMark(EmptyRegAndVin.Please_select.getReasonDescription());
        selectReasonForEmptyVIN(EmptyRegAndVin.Please_select.getReasonDescription());
        setDay(Integer.toString(vehicle.dateOfFirstUse.getDayOfMonth()));
        setMonth(Integer.toString(vehicle.dateOfFirstUse.getMonthOfYear()));
        setYear(Integer.toString(vehicle.dateOfFirstUse.getYear()));
        selectMakeOfVehicle(vehicle.make);
        selectCountryOfRegistration(vehicle.countryOfRegistration);
        selectTransmissionType(vehicle.transType);
        return this;
    }

    public CreateNewVehicleRecordVehicleIdentificationPage enterVehicleDetailsWithMakeTypeOther(
            Vehicle vehicle) {
        setDay(Integer.toString(vehicle.dateOfFirstUse.getDayOfMonth()));
        setMonth(Integer.toString(vehicle.dateOfFirstUse.getMonthOfYear()));
        setYear(Integer.toString(vehicle.dateOfFirstUse.getYear()));
        selectMakeOfVehicle(vehicle.make.Other);
        selectCountryOfRegistration(vehicle.countryOfRegistration);
        selectTransmissionType(vehicle.transType);
        return this;
    }

    public CreateNewVehicleRecordVehicleIdentificationPage enterOtherMake() {
        otherMake.sendKeys("NewVehicleMake7ToTestDisplayed");
        return this;
    }

    public CreateNewVehicleRecordVehicleIdentificationPage editVehicleReg(
            String registration_Mark) {
        registrationNumber.clear();
        registrationNumber.sendKeys(registration_Mark);
        return this;
    }


    public CreateNewVehicleRecordVehicleIdentificationPage editVehicleVin(String vehicle_Vin) {
        vin.clear();
        vin.sendKeys(vehicle_Vin);
        return this;
    }

    public boolean isCorrectDetailsDisplayed(Vehicle vehicle) {

        return (registrationNumber.getAttribute("value").equalsIgnoreCase(vehicle.carReg) && vin
                .getAttribute("value").equalsIgnoreCase(vehicle.fullVIN)) &&
                day.getAttribute("value")
                        .equalsIgnoreCase(Integer.toString(vehicle.dateOfFirstUse.getDayOfMonth()))
                &&
                month.getAttribute("value")
                        .equalsIgnoreCase(Integer.toString(vehicle.dateOfFirstUse.getMonthOfYear()))
                &&
                year.getAttribute("value")
                        .equalsIgnoreCase(Integer.toString(vehicle.dateOfFirstUse.getYear())) &&
                registrationCountry.getAttribute("value").equalsIgnoreCase(
                        vehicle.countryOfRegistration.getcountryOfRegistrationID()) &&
                make.getAttribute("value").equalsIgnoreCase(vehicle.make.getVehicleID()) &&
                getTransmissionTypeRadioList().findSelected().getAttribute("value")
                        .equalsIgnoreCase(vehicle.transType.getTransmissionId());
    }

    public CreateNewVehicleRecordVehicleSpecificationPage enterVehicleDetailsAndSubmit(
            Vehicle vehicle) {
        enterVehicleDetails(vehicle);
        return submit();
    }

    public CreateNewVehicleRecordVehicleIdentificationPage submitDetailsExpectingError() {
        submit.click();
        return new CreateNewVehicleRecordVehicleIdentificationPage(driver);
    }

    public CreateNewVehicleRecordVehicleIdentificationPage submitDetailsWithoutRegAndVin() {
        registrationNumber.clear();
        vin.clear();
        submit.click();
        return new CreateNewVehicleRecordVehicleIdentificationPage(driver);
    }

    public VehicleSearchPage cancelReturnToVehicleSearch() {
        cancel.click();
        return new VehicleSearchPage(driver);
    }

    public int getNumberOfCountriesRegistered() {
        Select selectCountryOfRegistration = new Select(registrationCountry);
        List<WebElement> countryList = selectCountryOfRegistration.getOptions();
        return countryList.size();

    }

    public CreateNewVehicleRecordVehicleIdentificationPage submitDetailsWithRegForUKVehicles(
            Vehicle vehicle) {
        registrationNumber.sendKeys("12345678");
        setVin(vehicle.fullVIN);
        setDay(Integer.toString(vehicle.dateOfFirstUse.getDayOfMonth()));
        setMonth(Integer.toString(vehicle.dateOfFirstUse.getMonthOfYear()));
        setYear(Integer.toString(vehicle.dateOfFirstUse.getYear()));
        selectMakeOfVehicle(vehicle.make);
        selectTransmissionType(vehicle.transType);
        return new CreateNewVehicleRecordVehicleIdentificationPage(driver);
    }

    public CreateNewVehicleRecordVehicleIdentificationPage clearRegField() {
        registrationNumber.clear();
        return new CreateNewVehicleRecordVehicleIdentificationPage(driver);
    }

    public CreateNewVehicleRecordVehicleIdentificationPage clearVinField() {
        vin.clear();
        return new CreateNewVehicleRecordVehicleIdentificationPage(driver);
    }

    public CreateNewVehicleRecordVehicleIdentificationPage enterRegValueWithNonUKSelected() {
        registrationNumber.sendKeys("12345678");
        selectCountryOfRegistration(CountryOfRegistration.Poland);
        return new CreateNewVehicleRecordVehicleIdentificationPage(driver);
    }

    public CreateNewVehicleRecordVehicleIdentificationPage enterVinValue(Vehicle vehicle) {
        vin.clear();
        vin.sendKeys(vehicle.fullVIN);
        return this;
    }

    public CreateNewVehicleRecordVehicleIdentificationPage selectReasonForEmptyRegMark(
            String vrmReason) {
        Select select = new Select(emptyVrmReason);
        select.selectByVisibleText(vrmReason);
        return new CreateNewVehicleRecordVehicleIdentificationPage(driver);
    }

    public CreateNewVehicleRecordVehicleIdentificationPage selectReasonForEmptyVIN(
            String vinReason) {
        Select select = new Select(emptyVinReason);
        select.selectByVisibleText(vinReason);
        return new CreateNewVehicleRecordVehicleIdentificationPage(driver);
    }
    public CreateNewVehicleRecordVehicleIdentificationPage enterRegAndVinValues(Vehicle vehicle){
        setRegistrationNumber(vehicle.carReg);
        setVin(vehicle.fullVIN);
        return this;
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
