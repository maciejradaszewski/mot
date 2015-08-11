package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class VehicleConfirmationRetestPage extends StartTestConfirmation1Page {
    public static String CONFIRMATION_RETEST_PAGE_TITLE = "FIND A VEHICLE";
    public static String MOT_RETEST_STARTED_PAGE_TITLE = "MOT RETEST STARTED";

    @FindBy(id = "confirm_vehicle_confirmation") private WebElement confirmButton;

    @FindBy(css = ".status.status-danger") private WebElement notQualifiedForRetestMsg;

    @FindBy(xpath = "//a[@href='/retest-vehicle-search']") private WebElement searchAgain;

    public VehicleConfirmationRetestPage(WebDriver driver) {
        super(driver, CONFIRMATION_RETEST_PAGE_TITLE);
    }

    public static VehicleConfirmationRetestPage navigateHereFromLoginPage(WebDriver driver,
            Login login, Vehicle vehicle) {
        return VehicleSearchRetestPage.navigateHereFromLoginPage(driver, login)
                .submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg);
    }

    public static VehicleConfirmationRetestPage navigateHereFromLoginPage_PreviousNo(
            WebDriver driver, Login login, String previousTestNo) {
        return VehicleSearchRetestPage.navigateHereFromLoginPage(driver, login)
                .submitSearchWithPreviousTestNumber(previousTestNo);
    }

    public MotTestStartedPage submitConfirm() {
        confirmButton.click();

        if (exist2FAFieldInCurrentPage()) {
            VehicleDetailsChangedPage vehicleDetailsChangedPage =
                    new VehicleDetailsChangedPage(driver);
            return vehicleDetailsChangedPage
                    .confirmVehicleChangesExpectingMottestOptionsPage(Text.TEXT_PASSCODE);
        } else {
            return new MotTestStartedPage(driver, MOT_RETEST_STARTED_PAGE_TITLE);
        }
    }

    @Override public MOTRetestPage startTest() {
        return submitConfirm().returnToHome().resumeMotTestExpectingMOTRetestPage();
    }

    public VehicleConfirmationRetestPage submitConfirmExpectingError() {
        confirmButton.click();
        return new VehicleConfirmationRetestPage(driver);
    }

    public VehicleSearchRetestPage searchAgain() {
        searchAgain.click();
        return new VehicleSearchRetestPage(driver);
    }

}
