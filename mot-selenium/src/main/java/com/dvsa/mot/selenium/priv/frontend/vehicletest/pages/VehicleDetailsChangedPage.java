package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class VehicleDetailsChangedPage extends BasePage {
    public static final String PAGE_TITLE = "VEHICLE DETAILS CHANGED";

    @FindBy(id = "oneTimePassword") private WebElement oneTimePassword;

    @FindBy(id = "confirm_vehicle_changes") private WebElement confirmButton;

    public VehicleDetailsChangedPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public VehicleDetailsChangedPage enterOneTimePassword(String password) {
        oneTimePassword.sendKeys(password);
        return this;
    }

    public MotTestStartedPage confirmVehicleChanges(String password) {
        enterOneTimePassword(password);
        confirmButton.click();
        return new MotTestStartedPage(driver);
    }

    public MotTestStartedPage confirmVehicleChangesExpectingMottestOptionsPage(String password) {
        enterOneTimePassword(password);
        confirmButton.click();
        return new MotTestStartedPage(driver);
    }

}
