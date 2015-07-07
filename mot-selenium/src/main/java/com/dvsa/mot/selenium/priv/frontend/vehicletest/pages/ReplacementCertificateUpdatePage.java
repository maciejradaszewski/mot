package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;


import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.enums.Colour;
import com.dvsa.mot.selenium.datasource.enums.VehicleMake;
import com.dvsa.mot.selenium.datasource.enums.VehicleModel;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.Select;
import org.openqa.selenium.support.ui.WebDriverWait;

public class ReplacementCertificateUpdatePage extends BasePage {

    private static String REPLACEMENT_CERT_UPDATE_PAGE = "REPLACEMENT CERTIFICATE UPDATE";

    @FindBy(id = "cancelMotTest") private WebElement cancelEdit;

    //EDIT MAKE AND MODEL
    @FindBy(id = "dashboard-section-toggler-make") private WebElement editMakeButton;

    @FindBy(id = "dashboard-section-header-value-make") private WebElement makeText;

    @FindBy(id = "input-make") private WebElement makeDropdownList;

    @FindBy(id = "section-make-submit") private WebElement submitMakeButton;

    @FindBy(id = "dashboard-section-toggler-model") private WebElement editModelButton;

    @FindBy(id = "dashboard-section-header-value-model") private WebElement modelText;

    @FindBy(id = "input-model") private WebElement modelDropdownList;

    @FindBy(id = "section-model-submit") private WebElement submitModelButton;

    //EDIT ODOMETER READING
    @FindBy(id = "dashboard-section-toggler-odometer") private WebElement updateOdometerReading;

    @FindBy(id = "odometer") private WebElement enterOdometerReading;

    @FindBy(id = "notReadable") private WebElement odometerNotReadableOption;

    @FindBy(id = "noOdometer") private WebElement noOdometerOption;

    @FindBy(id = "section-odometer-submit") private WebElement submitOdometerReading;

    //EDIT VEHICLE COLOUR
    @FindBy(id = "dashboard-section-toggler-vehicle-colour") private WebElement editColour;

    @FindBy(id = "select-primary-colour") private WebElement selectPrimaryColour;

    @FindBy(id = "select-secondary-colour") private WebElement selectSecondaryColour;

    @FindBy(id = "section-vehicle-colour-submit") private WebElement submitColour;

    //EDIT VTS LOCATION
    @FindBy(id = "dashboard-section-toggler-vts") private WebElement editTestingLocation;

    @FindBy(id = "select2-drop") private WebElement vtsSearchContent;

    @FindBy(id = "input-vts") private WebElement vtsSearchBox;

    @FindBy(xpath = "//*[@id='dashboard-section-body-vts']//*[contains(@class, 'select2-chosen')]")
    private WebElement vtsSearchBoxMask;

    @FindBy(id = "section-vts-submit") private WebElement submitVTSLocation;

    //EDIT VIN
    @FindBy(id = "dashboard-section-toggler-vin") private WebElement editVIN;

    @FindBy(id = "input-vin") private WebElement enterVIN;

    @FindBy(id = "section-vin-submit") private WebElement submitVIN;

    //EDIT REGISTRATION
    @FindBy(id = "dashboard-section-toggler-vrm") private WebElement editRegistration;

    @FindBy(id = "input-vrm") private WebElement enterVRM;

    @FindBy(id = "section-vrm-submit") private WebElement submitVRM;

    //EDIT COUNTRY OF REGISTRATION
    @FindBy(id = "dashboard-section-toggler-cor") private WebElement editCountryOfRegistration;

    @FindBy(id = "input-cor") private WebElement countryOfRegistrationList;

    //INSERT REASON FOR REPLACEMENT
    @FindBy(id = "input-reason-for-replacement") private WebElement enterReasonForReplacement;

    //REVIEW CHANGES
    @FindBy(id = "updateCertificate") private WebElement reviewChanges;


    public ReplacementCertificateUpdatePage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(REPLACEMENT_CERT_UPDATE_PAGE);
    }

    public static ReplacementCertificateUpdatePage navigateHereFromLoginPage(WebDriver driver,
            Login login, Vehicle vehicle, String testNumber) {
        return DuplicateReplacementCertificatePage.navigateHereFromLoginPage(driver, login, vehicle)
                .clickEditByMOTNumber(testNumber);
    }

    public DuplicateReplacementCertificateSearchPage cancelEdit() {
        cancelEdit.click();
        return new DuplicateReplacementCertificateSearchPage(driver);
    }

    public ReplacementCertificateUpdatePage enterOdometerReading(String reading) {
        enterOdometerReading.sendKeys(reading);
        return this;
    }

    public ReplacementCertificateUpdatePage selectOdometerNotReadableOption() {
        odometerNotReadableOption.click();
        return this;
    }

    public ReplacementCertificateUpdatePage selectNoOdometerOption() {
        noOdometerOption.click();
        return this;
    }

    public ReplacementCertificateUpdatePage selectPrimaryColour(Colour colour) {
        Select primaryColour = new Select(selectPrimaryColour);
        primaryColour.selectByVisibleText(colour.getColourName());
        return this;
    }

    public ReplacementCertificateUpdatePage selectSecondaryColour(Colour colour) {
        Select secondaryColour = new Select(selectSecondaryColour);
        secondaryColour.selectByVisibleText(colour.getColourName());
        return this;
    }

    public ReplacementCertificateUpdatePage editColoursAndSubmit(Colour colour, Colour colour2) {
        editColour.click();
        selectPrimaryColour(colour);
        selectSecondaryColour(colour2);
        submitColour.click();
        return this;
    }

    public ReplacementCertificateUpdatePage enterVtsNameInSearchBox(String vtsName) {
        vtsSearchBox.clear();
        vtsSearchBox.sendKeys(vtsName);
        return this;
    }

    public ReplacementCertificateUpdatePage editVTSLocationAndSubmit(String vtsNumber) {
        editTestingLocation.click();
        enterVtsNameInSearchBox(vtsNumber);
        submitVTSLocation.click();
        return this;
    }

    public ReplacementCertificateUpdatePage editCountryOfRegistrationAndSubmit() {
        editCountryOfRegistration.click();
        countryOfRegistrationList.findElement(By.id("input-cor_2"));
        WebElement submitCountryOfRegistration = new WebDriverWait(driver, 10)
                .until(ExpectedConditions
                        .visibilityOf(driver.findElement(By.id("section-cor-submit"))));
        submitCountryOfRegistration.click();
        return this;
    }

    public ReplacementCertificateUpdatePage submitEditedOdometerInfo(String reading) {
        updateOdometerReading.click();
        waitForElementToBeVisible(enterOdometerReading, 1);
        enterOdometerReading.clear();
        enterOdometerReading(reading);
        waitForElementToBeVisible(submitOdometerReading, 1);
        submitOdometerReading.click();
        return this;
    }

    public ReplacementCertificateUpdatePage submitNoOdometerOption() {
        updateOdometerReading.click();
        selectNoOdometerOption();
        submitOdometerReading.click();
        return this;
    }

    public ReplacementCertificateUpdatePage submitOdometerNotReadableOption() {
        updateOdometerReading.click();
        selectOdometerNotReadableOption();
        submitOdometerReading.click();
        return this;
    }

    public ReplacementCertificateUpdatePage enterReasonForReplacement(String reason) {
        enterReasonForReplacement.click();
        enterReasonForReplacement.sendKeys(reason);
        return this;
    }

    public ReplacementCertificateReviewPage reviewChangesButton() {
        reviewChanges.click();
        return new ReplacementCertificateReviewPage(driver);
    }

    public ReplacementCertificateUpdatePage changeVehicleMake(VehicleMake vehicleMake) {
        editMakeButton.click();
        makeDropdownList.click();
        Select chooseMake = new Select(makeDropdownList);
        chooseMake.selectByValue(vehicleMake.getVehicleID());
        submitMakeButton.click();
        return this;
    }

    public String getMakeText() {
        return makeText.getText();
    }

    public ReplacementCertificateUpdatePage changeVehicleModel(VehicleModel vehicleModel) {
        editModelButton.click();
        Select chooseModel = new Select(modelDropdownList);
        chooseModel.selectByValue(vehicleModel.getModelId());
        submitModelButton.click();
        return this;
    }

    public String getModelText() {
        return modelText.getText();
    }

    public ChangeMakeAndModelPage enterVehicleMakeManually(VehicleMake vehicleMake) {
        editMakeButton.click();
        makeDropdownList.click();
        Select chooseMake = new Select(makeDropdownList);
        chooseMake.selectByValue(vehicleMake.getVehicleID());
        submitMakeButton.click();
        return new ChangeMakeAndModelPage(driver);
    }

    public ReplacementCertificateUpdatePage editMakeAndModelAndSubmit(VehicleMake vehicleMake,
            VehicleModel vehicleModel) {
        changeVehicleMake(vehicleMake);
        changeVehicleModel(vehicleModel);
        return this;
    }

    public ReplacementCertificateUpdatePage enterVIN(String vin) {
        enterVIN.sendKeys(vin);
        return this;
    }

    public ReplacementCertificateUpdatePage editVinAndSubmit(String vin) {
        editVIN.click();
        waitForElementToBeVisible(enterVIN, 1);
        enterVIN.clear();
        enterVIN(vin);
        waitForElementToBeVisible(submitVIN, 1);
        submitVIN.click();
        return this;

    }

    public ReplacementCertificateUpdatePage enterVRM(String reg) {
        enterVRM.sendKeys(reg);
        return this;
    }

    public ReplacementCertificateUpdatePage editVrmAndSubmit(String reg) {
        editRegistration.click();
        waitForElementToBeVisible(enterVRM, 1);
        enterVRM.clear();
        enterVRM(reg);
        waitForElementToBeVisible(submitVRM, 1);
        submitVRM.click();
        return this;

    }


}
