package com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages;

import com.dvsa.mot.selenium.datasource.BrakeTestConstants;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class ConfigureBrakeTestDefaultsPage extends BasePage {

    @FindBy(id = "ROLLR-default-brake-test-class-1-and-2-label") private WebElement
            rollerDefaultBrakeTestClass1And2;

    @FindBy(id = "DECEL-default-brake-test-class-1-and-2-label") private WebElement
            decelerometerDefaultBrakeTestClass1And2;

    @FindBy(id = "FLOOR-default-brake-test-class-1-and-2-label") private WebElement
            floorDefaultBrakeTestClass1And2;

    @FindBy(id = "GRADT-default-brake-test-class-1-and-2-label") private WebElement
            gradientDefaultBrakeTestClass1And2;

    @FindBy(id = "ROLLR-default-parking-brake-test-class-3-and-above-label") private WebElement
            rollerDefaultParkingBrakeTestClass3AndAbove;

    @FindBy(id = "PLATE-default-parking-brake-test-class-3-and-above-label") private WebElement
            plateDefaultParkingBrakeTestClass3AndAbove;

    @FindBy(id = "DECEL-default-parking-brake-test-class-3-and-above-label")
    private WebElement decelerometerDefaultParkingBrakeTestClass3AndAbove;

    @FindBy(id = "GRADT-default-parking-brake-test-class-3-and-above") private WebElement
            gradientDefaultParkingBrakeTestClass3AndAbove;

    @FindBy(id = "ROLLR-default-service-brake-test-class-3-and-above-label") private WebElement
            rollerDefaultServiceBrakeTestClass3AndAbove;

    @FindBy(id = "PLATE-default-service-brake-test-class-3-and-above-label") private WebElement
            plateDefaultServiceBrakeTestClass3AndAbove;

    @FindBy(id = "DECEL-default-service-brake-test-class-3-and-above-label")
    private WebElement decelerometerDefaultServiceBrakeTestClass3AndAbove;

    @FindBy(id = "save") private WebElement saveButton;

    @FindBy(id = "cancel") private WebElement goBackToVtsOverviewPage;

    public ConfigureBrakeTestDefaultsPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public SiteDetailsPage clickSaveButton() {
        saveButton.click();
        return new SiteDetailsPage(driver);
    }

    public ConfigureBrakeTestDefaultsPage selectABrakeTestType(
            BrakeTestConstants.BrakeTestType brakeTestType) {

        switch (brakeTestType) {
            case Roller:
                rollerDefaultBrakeTestClass1And2.click();
                break;
            case Decelerometer:
                decelerometerDefaultBrakeTestClass1And2.click();
                break;
            case Floor:
                floorDefaultBrakeTestClass1And2.click();
                break;
            case Gradient:
                gradientDefaultBrakeTestClass1And2.click();
                break;
            case Plate:
                plateDefaultParkingBrakeTestClass3AndAbove.click();
                break;
            case ClassB:
                plateDefaultServiceBrakeTestClass3AndAbove.click();
                plateDefaultParkingBrakeTestClass3AndAbove.click();

            default:
                break;
        }
        return this;
    }

    public boolean isSelectedSiteDisplaysTheCorrectVehicleClass() {
        SiteDetailsPage siteDetailsPage = new SiteDetailsPage(driver);
        if (driver.getPageSource().contains(siteDetailsPage.getSiteClasses())) {
            return true;
        } else
            return false;
    }

    public String getDefaultParkingBrakeTestType() {
        return rollerDefaultBrakeTestClass1And2.getText();
    }

    public String getPlateDefaultParkingBrakeTestClass3AndAbove() {
        System.out.println(plateDefaultParkingBrakeTestClass3AndAbove.getText());
        return plateDefaultParkingBrakeTestClass3AndAbove.getText();
    }

    public String getPlateDefaultServiceBrakeTestClass3AndAbove() {
        System.out.println(plateDefaultServiceBrakeTestClass3AndAbove.getText());
        return plateDefaultServiceBrakeTestClass3AndAbove.getText();
    }
}
