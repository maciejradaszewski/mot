package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class VehicleSearchRetestPage extends VehicleSearchPage {

    private static String VEHICLE_SEARCH_RETEST_PAGE_TITLE = "VEHICLE SEARCH - RETEST";

    @FindBy(id = "test-number-input") private WebElement previousTestNumber;

    @FindBy(css = ".col-sm-6.col-md-5.col-lg-4") private WebElement retestSearchForm;

    public VehicleSearchRetestPage(WebDriver driver) {
        super(driver, VEHICLE_SEARCH_RETEST_PAGE_TITLE);
    }

    public static VehicleSearchRetestPage navigateHereFromLoginPage(WebDriver driver, Login login) {
        if (!login.isManyVtsTester) {
            return UserDashboardPage.navigateHereFromLoginPage(driver, login).startMotRetest();
        } else {
            UserDashboardPage.navigateHereFromLoginPage(driver, login)
                    .startMotRetestAsManyVtsTesterWithoutVtsChosen().selectAndConfirmFirstVts();
            return new VehicleSearchRetestPage(driver);
        }
    }

    public VehicleSearchRetestPage enterPreviousTestNumber(String previousTestNumber) {
        this.previousTestNumber.sendKeys(previousTestNumber);
        return this;
    }

    public VehicleConfirmationRetestPage submitSearchWithPreviousTestNumber(
            String previousTestNumber) {
        enterPreviousTestNumber(previousTestNumber);
        return submitSearch();
    }

    public VehicleConfirmationRetestPage submitSearch() {
        clickSearch();
        if(getPageTitle().matches(VEHICLE_SEARCH_RETEST_PAGE_TITLE)) {
            clickSearch();
        }
        return new VehicleConfirmationRetestPage(driver);
    }

    public VehicleSearchRetestPage submitSearchRetestExpectingError() {
        clickSearch();
        return new VehicleSearchRetestPage(driver);
    }

    public VehicleConfirmationRetestPage submitSearchWithVinAndReg(String vin, String reg) {
        typeVIN(vin);
        typeReg(reg);
        return submitSearch();
    }

    public VehicleSearchRetestPage submitSearchWithPreviousTestNumberExpectingError(
            String previousTestNumber) {
        enterPreviousTestNumber(previousTestNumber);
        return submitSearchRetestExpectingError();
    }

    public VehicleSearchRetestPage submitSearchWithVinAndRegExpectingError(String vin, String reg) {
        typeVIN(vin);
        typeReg(reg);
        return submitSearchRetestExpectingError();
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }

    @Override public Boolean isVehicleSearchFormDisplayed() {

        return isElementDisplayed(retestSearchForm);
    }
}
