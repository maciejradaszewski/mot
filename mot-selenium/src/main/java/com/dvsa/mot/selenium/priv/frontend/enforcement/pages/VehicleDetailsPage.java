package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.enums.Colour;
import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.testng.Assert;

public class VehicleDetailsPage extends BasePage {
    public static final String PAGE_TITLE = "Vehicle Details";



    @FindBy(xpath = "//h1") private WebElement title;

    @FindBy(id = "regNr") private WebElement regNr;

    @FindBy(id = "vin") private WebElement vin;

    @FindBy(id = "make") private WebElement make;

    @FindBy(id = "model") private WebElement model;

    @FindBy(id = "bodyType") private WebElement bodyType;

    @FindBy(id = "color") private WebElement color;

    @FindBy(id = "fuel") private WebElement fuel;

    @FindBy(id = "created") private WebElement created;

    @FindBy(id = "noSeats") private WebElement noSeats;

    @FindBy(id = "noSeatBelts") private WebElement noSeatBelts;

    @FindBy(id = "dateOfSeatBelt") private WebElement dateOfSeatBelt;

    @FindBy(id = "dateOfMake") private WebElement dateOfMake;

    @FindBy(id = "declaredNew") private WebElement declaredNew;

    @FindBy(id = "dateOfReg") private WebElement dateOfReg;

    @FindBy(id = "firstUseDate") private WebElement firstUseDate;

    @FindBy(id = "cylinder") private WebElement cylinder;

    @FindBy(id = "historyLink") private WebElement historyLink;

    @FindBy(partialLinkText = "Go back") private WebElement goBack;

    @FindBy(id = "returnDashboard") private WebElement returnToDashboard;

    @FindBy(partialLinkText = "View") private WebElement view;

    public VehicleDetailsPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PageTitles.MOT_VEHICLE_DETAILS_PAGE.getPageTitle());
    }

    public VehicleDetailsPage verifyPageTitle() {
        Assert.assertEquals(title.getText(), PAGE_TITLE, "Assert the page title is correct");
        return new VehicleDetailsPage(driver);
    }


    public TestHistoryPage clickHistoryLink() {
        view.click();
        return new TestHistoryPage(driver);
    }

    public VehicleInformationPage clickGoBackLink() {
        goBack.click();
        return new VehicleInformationPage(driver);
    }

    public VehicleDetailsPage verifyPageElements() {
        Assert.assertTrue(regNr.isDisplayed() && vin.isDisplayed() && make.isDisplayed() && model
                .isDisplayed() && bodyType.isDisplayed() && color.isDisplayed() && fuel
                .isDisplayed() && created.isDisplayed() && noSeats.isDisplayed() && cylinder
                .isDisplayed() && noSeatBelts.isDisplayed() && dateOfSeatBelt.isDisplayed()
                && dateOfMake.isDisplayed() && historyLink.isDisplayed() && declaredNew
                .isDisplayed() && dateOfReg.isDisplayed() && firstUseDate.isDisplayed());
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifyRegNumber(String regNumber) {
        Assert.assertEquals(regNr.getText(), regNumber);
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifyVin(String vinNumber) {
        Assert.assertEquals(vin.getText(), vinNumber);
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifyMake(String makeText) {
        Assert.assertEquals(make.getText(), makeText);
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifyModel(String modelText) {
        Assert.assertEquals(model.getText(), modelText);
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifyModelType(String modelType) {
        Assert.assertEquals(bodyType.getText(), modelType);
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifyFuelType(String fuelType) {
        Assert.assertEquals(fuel.getText(), fuelType);
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifyCylinderCapacity(int cylinderCapacity) {
        Assert.assertEquals(Integer.parseInt(cylinder.getText()), cylinderCapacity);
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifyColor(String primaryColour, String secondaryColour) {
        Assert.assertEquals(color.getText(), getColour(primaryColour, secondaryColour));
        return new VehicleDetailsPage(driver);
    }

    private String getColour(String primaryColour, String secondaryColour) {
        if (primaryColour.equals(secondaryColour))
            return secondaryColour;
        else if (secondaryColour.equals(Colour.NotStated.getColourName()))
            return primaryColour;
        else
            return (primaryColour + "and" + secondaryColour);
    }

    public VehicleDetailsPage verifyDetailsCreated() {
        Assert.assertTrue(created.isDisplayed());
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifyManufactureDate(String date) {
        Assert.assertEquals(dateOfMake.getText(), date);
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifyNoOfSeats(String seatCount) {
        Assert.assertEquals(noSeats.getText(), seatCount);
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifyDeclaredNew(String declaration) {
        Assert.assertEquals(declaredNew.getText(), declaration);
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifyRegistrationDate(String date) {
        Assert.assertEquals(dateOfReg.getText(), date);
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifyNoOfSeatBelts(String seatBeltsCount) {
        Assert.assertEquals(noSeatBelts.getText(), seatBeltsCount);
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifySeatBeltLastCheckedDate(String date) {
        Assert.assertEquals(dateOfSeatBelt.getText(), date);
        return new VehicleDetailsPage(driver);
    }

    public VehicleDetailsPage verifyFirstUseDate(String date) {
        Assert.assertEquals(firstUseDate.getText(), date);
        return new VehicleDetailsPage(driver);
    }

}
