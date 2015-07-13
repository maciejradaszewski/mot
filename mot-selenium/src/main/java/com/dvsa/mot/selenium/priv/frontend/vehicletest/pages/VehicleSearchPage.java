package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class VehicleSearchPage extends BasePage {

    protected PageTitles pageTitles;
    private final String DEFAULT_TITLE = pageTitles.VEHICLE_SEARCH_PAGE.getPageTitle();

    @FindBy(id = "vin-info") private WebElement vinInfo;

    @FindBy(id = "main-message") private WebElement mainMessage;

    @FindBy(id = "additional-message") private WebElement additionalMessage;

    @FindBy(name = "registration") protected WebElement registrationField;

    @FindBy(name = "vin") private WebElement vinField;

    @FindBy(id = "cancel_vehicle_search") private WebElement cancelButton;

    @FindBy(id = "vehicle-search") private WebElement searchButton;

    @FindBy(id = "vin-type-select") private WebElement vinTypeSelect;

    @FindBy(id = "VehicleSearch") private WebElement vehicleSearchForm;

    @FindBy(id = "new-vehicle-record-link") private WebElement createNewVehicleLink;

    @FindBy(xpath = ".//p[contains(., 'No matches were found for VIN')]") private WebElement
            noVinMatchErrorBox;

    @FindBy(xpath = "id('results-table')//td[1]//a") private WebElement firstResultCTA;

    @FindBy(id = "results-table") private WebElement vehicleInfoTable;

    @FindBy(id = "new-vehicle-record-info") private WebElement createNewVehicleInfo;

    @FindBy(css = ".search-group") protected WebElement searchForm;

    public VehicleSearchPage(WebDriver driver) {
        super(driver);
        checkTitle(DEFAULT_TITLE);
    }

    public static VehicleSearchPage navigateHereFromLoginPage(WebDriver driver, Login login) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login).startMotTest();
    }

    public static VehicleSearchPage navigateHereFromLoginPageForManyVtsTester(WebDriver driver,
            Login login, Site site) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login)
                .startMotTestAsManyVtsTesterWithoutVtsChosen().selectAndConfirmVTS(site);
    }

    public VehicleSearchPage(WebDriver driver, String pageTitle) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(pageTitle);
    }

    public VehicleSearchPage submitSearchExpectingFailure() {
        clickSearch();
        return new VehicleSearchPage(driver);
    }

    /**
     * Inserts provided VIN and submits the form expecting failure
     *
     * @param vin
     * @return the same page
     */
    public VehicleSearchPage submitSearchWithVinExpectingFail(String vin) {
        typeVIN(vin);
        return submitSearchExpectingFailure();
    }

    public VehicleSearchPage submitSearchWithRegOnlyExpectingVehicleSearchPage(String reg) {
        typeReg(reg);
        return submitSearchExpectingError();
    }

    public StartTestConfirmation1Page submitSearch(Vehicle vehicle) {
        return submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg);
    }

    public VehicleSearchPage submitSearchWithVinOnly(String vin) {
        typeVIN(vin);
        clickSearch();
        return this;
    }

    public StartTestConfirmation1Page submitSearchWithVinAndReg(String vin, String reg) {
        typeVIN(vin);
        typeReg(reg);
        clickSearch();
        clickVehicleCTA();
        return new StartTestConfirmation1Page(driver);
    }


    /**
     * Inserts provided VIN and Reg and submits the form expecting failure
     *
     * @param vin
     * @param reg
     * @return the same page
     */
    public VehicleSearchPage submitSearchWithVinAndRegExpectingError(String vin, String reg) {
        typeVIN(vin);
        typeReg(reg);
        return submitSearchExpectingFailure();
    }

    public StartTestConfirmation1Page submitSearch() {
        clickSearch();
        return new StartTestConfirmation1Page(driver);
    }

    public StartTestConfirmation1Page clickVehicleCTA() {
        firstResultCTA.click();
        return new StartTestConfirmation1Page(driver);
    }

    private String getVehicleInfo() {
        return vehicleInfoTable.getText();
    }

    public boolean verifyRegistrationPresent() {
        String vehicleInfo = getVehicleInfo();
        return vehicleInfo.contains("Registration mark");
    }

    public VehicleSearchPage submitSearchExpectingError() {
        clickSearch();
        return new VehicleSearchPage(driver);
    }

    public VehicleSearchPage clickSearch() {
        searchButton.click();
        return this;
    }

    public UserDashboardPage clickCancel() {
        cancelButton.click();
        return new UserDashboardPage(driver);
    }

    public VehicleSearchPage typeVIN(String vin) {
        vinField.clear();
        vinField.sendKeys(vin);
        return this;
    }

    public VehicleSearchPage typeReg(String reg) {
        registrationField.clear();
        registrationField.sendKeys(reg);
        return this;
    }

    public String getMainMessageInfoText() {
        return mainMessage.getText();
    }

    public String getAdditionalMessageInfo() {
        return additionalMessage.getText();
    }

    public String getCreateNewVehicleInfoText() {
        return createNewVehicleInfo.getText();
    }

    public CreateNewVehicleRecordVehicleIdentificationPage createNewVehicle() {
        createNewVehicleLink.click();
        return new CreateNewVehicleRecordVehicleIdentificationPage(driver);
    }

    public boolean isCreateNewVehicleRecordLinkPresent() {
        return isElementClickable(createNewVehicleLink, 5);
    }

    public Boolean isVehicleSearchFormDisplayed() {

        return isElementDisplayed(searchForm);
    }
    public boolean isCookieElementPresentInDOM() {
        String cookieLink =  "id('global-cookie-message')//a";
        return isElementPresent(By.xpath(cookieLink));
    }

}
