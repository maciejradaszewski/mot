package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;
import org.testng.Assert;

public class VehicleInformationPage extends BasePage {
    public static final String PAGE_TITLE = "Vehicle Search Results";

    @FindBy(xpath = "//h1") private WebElement title;

    @FindBy(xpath = "//h2") private WebElement vehiclesFoundInformation;

    @FindBy(xpath = "id('listVehicles')/tbody/tr[1]/td[2]") private WebElement tableVrm;

    @FindBy(xpath = "id('listVehicles')/tbody/tr[1]/td[1]") private WebElement tableVin;

    @FindBy(partialLinkText = "go back") private WebElement goBackLink;

    @FindBy(partialLinkText = "Details") private WebElement details;

    @FindBy(xpath = "id('listVehicles')/tbody/tr[1]/td[3]") private WebElement viewLink;

    @FindBy(id = "type") private WebElement searchVehicleByType;

    @FindBy(id = "vehicle-search") private WebElement searchTextField;

    @FindBy(id = "item-selector-btn-search") private WebElement submitSearch;

    @FindBy(xpath = "id('listVehicles_filter')/label/input") private WebElement filter;

    public VehicleInformationPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public VehicleInformationPage verifyPageTitle() {
        Assert.assertEquals(title.getText(), PAGE_TITLE, "Assert the page title is correct");
        return new VehicleInformationPage(driver);
    }

    public VehicleInformationPage verifyVrmsFoundTitle(String searchTerm) {
        String vehiclesInformationTitle =
                Assertion.ASSERTION_VRMS_FOUND_INFORMATION.assertion + "\"" + searchTerm + "\"";
        Assert.assertEquals(vehiclesFoundInformation.getText(), vehiclesInformationTitle,
                "Assert the VRM search information is displayed");
        return new VehicleInformationPage(driver);
    }

    public VehicleInformationPage verifyVinsFoundTitle(String searchTerm) {
        String vehiclesInformationTitle =
                Assertion.ASSERTION_VINS_FOUND_INFORMATION.assertion + "\"" + searchTerm + "\"";
        Assert.assertEquals(vehiclesFoundInformation.getText(), vehiclesInformationTitle,
                "Assert the VRM search information is displayed");
        return new VehicleInformationPage(driver);
    }

    public VehicleInformationPage verifyVrmsTable(String searchTerm) {
        Assert.assertEquals(tableVrm.getText(), searchTerm,
                "Assert the searched VRM is displayed in the table");
        return new VehicleInformationPage(driver);
    }

    public VehicleInformationPage verifyVinsTable(String searchTerm) {
        Assert.assertEquals(tableVin.getText(), searchTerm,
                "Assert the searched VRM is displayed in the table");
        return new VehicleInformationPage(driver);
    }

    public VehicleDetailsPage clickDetailsLink() {
        details.click();
        return new VehicleDetailsPage(driver);
    }

    public VehicleInformationPage filterBy(String vehicleReg) {
        filter.sendKeys(vehicleReg);
        return this;
    }

    public SearchVehicleInformationPage clickGoBackLink() {
        goBackLink.click();
        return new SearchVehicleInformationPage(driver);
    }

    public VehicleInformationPage enterFilterText(String vehicle){
        filter.sendKeys(vehicle);
        return new VehicleInformationPage(driver);
    }

}
