package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;


import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class ChangeMakeAndModelPage extends BasePage {

    private static final String PAGE_TITLE = "DUPLICATE AND REPLACEMENT CERTIFICATES\n" +
            "CHANGE MAKE AND MODEL";

    @FindBy(id = "input-make")
    private WebElement vehicleMake;

    @FindBy(id = "input-model")
    private WebElement vehicleModel;

    @FindBy(className = "button")
    private WebElement submit;

    public ChangeMakeAndModelPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public ChangeMakeAndModelPage enterVehicleMake(String make) {
        vehicleMake.sendKeys(make);
        return this;
    }

    public ChangeMakeAndModelPage enterVehicleModel(String model) {
        vehicleModel.sendKeys(model);
        return this;
    }

    public ReplacementCertificateUpdatePage submitOtherMakeAndModel(String make, String model) {
        vehicleMake.sendKeys(make);
        vehicleModel.sendKeys(model);
        submit.click();
        return new ReplacementCertificateUpdatePage(driver);
    }

}
