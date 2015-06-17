package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;
import org.testng.Assert;

public class SearchVehicleInformationPage extends BasePage {
    public static final String PAGE_TITLE = "Search for vehicle information by...";

    @FindBy(id = "type") private WebElement typeDropDown;

    @FindBy(id = "vehicle-search") private WebElement vehicleSearch;

    @FindBy(id = "item-selector-btn-search") private WebElement btnSearch;

    @FindBy(id = "validation-summary-id") private WebElement validationErrors;

    @FindBy(xpath = "id('vehicle-search-form')//h1") private WebElement title;

    public SearchVehicleInformationPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public static SearchVehicleInformationPage navigateHereFromLoginPage(WebDriver driver,
            Login login) {
        return EnforcementHomePage.navigateHereFromLoginPage(driver, login)
                .clickVehicleInformation();
    }

    public SearchVehicleInformationPage verifyPageTitle() {
        Assert.assertEquals(title.getText(), PAGE_TITLE, "Assert the page title is correct");
        return new SearchVehicleInformationPage(driver);
    }

    public SearchVehicleInformationPage selectVehicleType(String vehicleDetails) {
        Select select = new Select(typeDropDown);
        select.selectByVisibleText(vehicleDetails);
        return this;
    }

    public VehicleInformationPage clickMultipleSearch() {
        btnSearch.click();
        return new VehicleInformationPage(driver);
    }

    public SearchVehicleInformationPage clickInvalidSearch() {
        btnSearch.click();
        return new SearchVehicleInformationPage(driver);
    }

    public VehicleDetailsPage clickSingleVehicleSearch() {
        btnSearch.click();
        return new VehicleDetailsPage(driver);
    }

    public String getValidationErrors() {
        return validationErrors.getText();
    }

    public VehicleDetailsPage submitVehicleInformationSearch(String search) {
        vehicleSearch.sendKeys(search);
        btnSearch.click();
        return new VehicleDetailsPage(driver);
    }

    public SearchVehicleInformationPage selectVehicleInfoType(String type) {
        Select typeDownBox = new Select(driver.findElement(By.id("type")));
        typeDownBox.selectByVisibleText(type);
        return new SearchVehicleInformationPage(driver);
    }

    public SearchVehicleInformationPage enterSearchTerm(String searchTerm) {
        vehicleSearch.sendKeys(searchTerm);
        return new SearchVehicleInformationPage(driver);
    }

    public SearchVehicleInformationPage clearSearchText() {
        vehicleSearch.clear();
        return new SearchVehicleInformationPage(driver);
    }
}
